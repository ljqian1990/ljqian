<?php
const REDIS_HOST = 'w2.dev.ztgame.com';
const REDIS_PORT = 6379;

const MEMKEY_WATCH_NUM = 'jl_act_match_ws_num';
const MEMKEY_USER_LOCK = 'jl_act_match_ws_user_lock_';
const MEMKEY_USER_DALIY_ONLINE_STARTTIME = 'jl_act_match_ws_user_online_starttime_';
const MEMKEY_USER_DALIY_ONLINE_LIVETIME = 'jl_act_match_ws_user_online_livetime_';
const SUBSCRIBE_CHANNEL = 'msg_0';

const BUFFER_SIZE = 262144;

const SPECIAL_SEPARATOR = '|;|;';
const EACH_SECOND_MASSGES_LIMITS = 10;

//单用户每次发送数据的间隔时间
const SEND_TIME_INTERVAL = 6;

/*
function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    throw new Exception("$errno,$errstr", 1);
}
*/
//set_error_handler('myErrorHandler');


$server = new swoole_websocket_server("0.0.0.0", 41010);
$server->set(['worker_num'=>64, 'max_request'=>0, 'max_conn'=>60000, 'daemonize'=>0, 'task_worker_num'=>40]);

$redisclient = null;
$redisSubscribeClient = null;

$server->on('task', function($server, $task_id, $from_id, $data){
    $dataArr = explode(SPECIAL_SEPARATOR, $data);
    $dataArr = array_filter($dataArr);
    $dataStr = json_encode($dataArr);
    foreach($server->connections as $fd) {
        if ($server->exist($fd)) {
            $server->push($fd, $dataStr);
        }
    }
    $server->finish('task OK');
});

$server->on('finish', function($server, $task_id, $data) {
//    echo $data.PHP_EOL;
});

$swooleBufferForMessagesGlobal = null;
$server->on('workerStart', function ($server, $workerId) {
    $jobType = $server->taskworker ? 'Tasker' : 'Worker';
    if ($jobType == 'Worker') {
        $redisSubscribeClient = new swoole_redis;
        
        // 创建内存对象，用来保存redis订阅收到的消息
        $swooleBuffer = new swoole_buffer(BUFFER_SIZE);
        global $swooleBufferForMessagesGlobal;
        $swooleBufferForMessagesGlobal = $swooleBuffer;
        
        // redis订阅消息回馈
        $redisSubscribeClient->on('message', function (swoole_redis $client, $result) use ($server, $swooleBufferForMessagesGlobal) {            
            if ($result[0] == 'message') {
                $swooleBufferForMessagesGlobal->append($result[2].SPECIAL_SEPARATOR);
                //$server->task($result[2]);
            }
        });
        
        // redis发起订阅请求
        $redisSubscribeClient->connect(REDIS_HOST, REDIS_PORT, function (swoole_redis $client, $result) use ($server) {
            $client->set(MEMKEY_WATCH_NUM, 0, function(swoole_redis $client, $result) use ($server){
                $client->subscribe(SUBSCRIBE_CHANNEL.'_'. $server->worker_id);
            });
        });
            
        // redis发起用于非订阅任务的请求
        $redisCommonClient = new swoole_redis;
        $redisCommonClient->connect(REDIS_HOST, REDIS_PORT, function (swoole_redis $client, $result) {
            global $redisclient;
            $redisclient = $client;
        }); 

        $server->tick(1000, function($id) use ($swooleBufferForMessagesGlobal, $server){
            $messages = $swooleBufferForMessagesGlobal->substr(0, -1, true);
            $swooleBufferForMessagesGlobal->clear();
            //$swooleBufferForMessagesGlobal->recycle();
            $mseeages = trim($messages);
                        
            if (! empty($messages)) {
                if (substr_count($messages, SPECIAL_SEPARATOR) > EACH_SECOND_MASSGES_LIMITS) {
                    $pos = newstripos($messages, SPECIAL_SEPARATOR, EACH_SECOND_MASSGES_LIMITS);
                    $messages = substr($messages, 0, $pos);                    
                }
                $server->task($messages);
            }
        });              
    } else {
        
    }
});

//
$fdToUuidTable= new swoole_table(262144);
$fdToUuidTable->column('uuid', swoole_table::TYPE_STRING, 50);
$fdToUuidTable->column('nickname', swoole_table::TYPE_STRING, 20);
$fdToUuidTable->create();

$uuidLockTable = new swoole_table(262144);
$uuidLockTable->column('ssolock', swoole_table::TYPE_INT, 1);
$uuidLockTable->column('lastSendTime', swoole_table::TYPE_STRING, 11);
$uuidLockTable->create();

$server->on('handshake', function(swoole_http_request $request, swoole_http_response $response) {
    global $redisclient;
    
    
    //$workerid = $server->worker_id;
    //echo $workerid.PHP_EOL;
    
    $userstr = $request->get['userstr'];
    //test
    $userstr = 'iny7Hn+TWsisBPHZc3xLIyuLgrvpNajPmxBftO5A3iMJ1fBWqHGoI2JcfgEcPE+lhCxYbNgzBgKdPpBtaUrt+mahAFsxL1g78kgryTaB2PASKPtmCyvDzvTDpsadIPNC4r5f60K3TSHN+kqEy6+GrSM0wrbUNNve/U+lny+xpp3D2ML/vFbc2h3aQUlAnwXq';
    if ($userinfo = decodeUser($userstr)) {
        
        global $server;
        
        global $uuidLockTable;        
        
        // 为了方便调试，在触发锁时，将锁去掉 
        //test
        $uuidLockTable->set($userinfo['uuid'], ['ssolock'=>0]);
        
        $ssolock = $uuidLockTable->get($userinfo['uuid'], 'ssolock');        
        if ($ssolock) {    
            // 已经被占用，提示错误
            $response->status(403);
            $response->end();
            return false;
        } else {
            // 尚未被占用，进入逻辑
            //$uuidLockTable->set($userinfo['uuid'], ['ssolock'=>1]); //test
            $uuidLockTable->set($userinfo['uuid'], ['ssolock'=>0]);
            
            global $fdToUuidTable;
            $workeridToFdKey = 'workerid_'.$server->worker_id.'_fd_'.$request->fd;
            if (! $fdToUuidTable->exist($workeridToFdKey)) {
                $fdToUuidTable->set($workeridToFdKey, ['uuid'=>$userinfo['uuid'], 'nickname'=>$userinfo['nickname']]);
            }
            
            // 处理websocket协议验证
            $secWebSocketKey = $request->header['sec-websocket-key'];
            $patten = '#^[+/0-9A-Za-z]{21}[AQgw]==$#';
            if (0 === preg_match($patten, $secWebSocketKey) || 16 !== strlen(base64_decode($secWebSocketKey))) {
                $response->end();
                return false;
            }
            //echo $request->header['sec-websocket-key'];
            $key = base64_encode(sha1(
                $request->header['sec-websocket-key'] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11',
                true
            ));

            $headers = [
                'Upgrade' => 'websocket',
                'Connection' => 'Upgrade',
                'Sec-WebSocket-Accept' => $key,
                'Sec-WebSocket-Version' => '13',
            ];

            if (isset($request->header['sec-websocket-protocol'])) {
                $headers['Sec-WebSocket-Protocol'] = $request->header['sec-websocket-protocol'];
            }

            foreach ($headers as $key => $val) {
                $response->header($key, $val);
            }

            $response->status(101);
            $response->end();
            
            $redisclient->incr(MEMKEY_WATCH_NUM, function(swoole_redis $client, $result) use ($userinfo){
                $client->set(MEMKEY_USER_DALIY_ONLINE_STARTTIME . $userinfo['uuid'], time(), function(swoole_redis $client, $result) {});
            });
        }
    } else {
        $response->status(401);
        $response->end();
        return false;
    }
    
    return true;
});

$server->on('message', function (swoole_websocket_server $server, $frame) {
    try {
        global $redisclient;        
        list($op, $recvdata) = explode(';', $frame->data);
        if ($op == 'count') {
            $redisclient->get(MEMKEY_WATCH_NUM, function(swoole_redis $client, $result) use ($server, $frame){
                $server->push($frame->fd, 'count:'.$result);               
            });
        } elseif ($op == 'broadcast') {
            global $fdToUuidTable;
            $workeridToFdKey = 'workerid_'.$server->worker_id.'_fd_'.$frame->fd;
            $useruuid = $fdToUuidTable->get($workeridToFdKey, 'uuid');
            
            // 存在最后一次发送数据时间，则判断当前时间与该时间的差值，如果差值大于单用户每次发送数据的时间差，则正常发送，如果小于时间差，就不做发送操作
            // 不存在最后一次发送数据时间，也就是用户第一次发送数据，则记录
            /* test
            global $uuidLockTable;
            $lastSendTime = $uuidLockTable->get($useruuid, 'lastSendTime');            
            if ($lastSendTime && ((time()-$lastSendTime) < SEND_TIME_INTERVAL)) {                
                return false;
            }
            
            
            $uuidLockTable->set($useruuid, ['lastSendTime'=>time()]);
            */
            
            $redisclient->publish(SUBSCRIBE_CHANNEL .'_'. $server->worker_id, $recvdata, function(swoole_redis $client, $result){
                
            });
        } elseif ($op == 'buffer') {
            global $swooleBufferForMessagesGlobal;
            //var_dump($swooleBufferForMessagesGlobal->substr(0, -1, true));
            //echo 'workerid:'.$server->worker_id.PHP_EOL;
        }
    } catch (Exception $ex) {
        $server->push($frame->fd, 'error:'.$ex->getMessage());
    } 
});

$server->on('close', function ($server, $fd) {
    try {
        global $redisclient;
        
        /**
         * 当用户退出时，将单用户登录锁取消
         */
        global $fdToUuidTable;
        
        global $uuidLockTable;
        
        if (isset($fdToUuidTable)) {
            $workeridToFdKey = 'workerid_'.$server->worker_id.'_fd_'.$fd;
            //echo $workeridToFdKey.PHP_EOL;
            $useruuid = $fdToUuidTable->get($workeridToFdKey, 'uuid');
            //echo 'uuid:'.$useruuid . PHP_EOL;
            if ($useruuid) {
                $fdToUuidTable->del($workeridToFdKey);
                $uuidLockTable->del($useruuid);
                
                // todo
                //$memkey = MEMKEY_USER_LOCK . $useruuid;
                //$redisclient->set($memkey, 0, function(swoole_redis $client, $result) use ($useruuid){
                    $redisclient->get(MEMKEY_USER_DALIY_ONLINE_STARTTIME . $useruuid, function(swoole_redis $client, $result) use ($useruuid) {
                        $starttime = (int)$result;
                        $onlineminute = floor((time()-$starttime)/60);
                        
                        $client->incrBy(MEMKEY_USER_DALIY_ONLINE_LIVETIME .date('Ymd').'_'. $useruuid, $onlineminute, function(swoole_redis $client, $result) {                        
                            $client->decr(MEMKEY_WATCH_NUM, function(swoole_redis $client, $result) {});
                        });
                    });
                //});    
            } else {
                $redisclient->decr(MEMKEY_WATCH_NUM, function(swoole_redis $client, $result) {});
            }
        } else {
            $redisclient->decr(MEMKEY_WATCH_NUM, function(swoole_redis $client, $result) {});
        }
    } catch (Exception $ex) {
        $server->push($fd, 'error:'.$ex->getMessage());
    }    
});

$server->on('workerStop', function($server, $worker_id) {
    global $redisclient;
    $redisclient->close();
    
    global $redisSubscribeClient;
    $redisSubscribeClient->close();
    
    global $swooleBufferForMessagesGlobal;
    $swooleBufferForMessagesGlobal->recycle();
});

$server->start(); 


function decodeUser($userstr)
{
    if (empty($userstr)) {
        return false;
    }
    
    $str = str_replace(' ', '+', $userstr);    
    $encryptedText = base64_decode($str);
    if (empty($encryptedText)) {
        return false;
    }
    
    $ret = stripPkcs7Padding(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, 'afcfc7966b28b6df', $encryptedText, MCRYPT_MODE_CBC, '2b41a307496edd66'));
    if (empty($ret)) {
        return false;
    }
    
    $userinfo = json_decode($ret, true);
    if (empty($userinfo) || empty($userinfo['area']) || empty($userinfo['nickname']) || empty($userinfo['uuid'])) {
        return false;
    }
    return $userinfo;
}

function stripPkcs7Padding($string)
{
    $slast = ord(substr($string, - 1));        
    $slastc = chr($slast);        
    $pcheck = substr($string, - $slast);
    
    if (preg_match("/$slastc{" . $slast . "}/", $string)) {            
        $string = substr($string, 0, strlen($string) - $slast);            
        return $string;
    } else {            
        return false;
    }
}

function newstripos($str, $find, $count, $offset=0)
{
	$pos = stripos($str, $find, $offset);
	$count--;
	if ($count > 0 && $pos !== FALSE) {
		$pos = newstripos($str, $find, $count, $pos+1);
	}
	return $pos;
}