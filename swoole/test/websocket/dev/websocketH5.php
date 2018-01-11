<?php
const REDIS_HOST = 'w2.dev.ztgame.com';
const REDIS_PORT = 6379;

const MEMKEY_WATCH_NUM = 'jl_act_match_ws_num';
const MEMKEY_USER_LOCK = 'jl_act_match_ws_user_lock_';
const MEMKEY_USER_DALIY_ONLINE_STARTTIME = 'jl_act_match_ws_user_online_starttime_';
const MEMKEY_USER_DALIY_ONLINE_LIVETIME = 'jl_act_match_ws_user_online_livetime_';
const SUBSCRIBE_CHANNEL = 'msg_0';

const BUFFER_SIZE = 8192;
const USER_BUFFER_SIZE = 32768;

const SPECIAL_SEPARATOR = '|;|;';

/*
function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    throw new Exception("$errno,$errstr", 1);
}
*/
//set_error_handler('myErrorHandler');


$server = new swoole_websocket_server("0.0.0.0", 41011);
$server->set(['worker_num'=>1, 'max_request'=>0, 'max_conn'=>20000, 'daemonize'=>0, 'task_worker_num'=>20]);

$redisclient = null;
$redisSubscribeClient = null;

$server->on('task', function($server, $task_id, $from_id, $data){
    $dataArr = explode(SPECIAL_SEPARATOR, $data);
    $dataArr = array_filter($dataArr);
    $dataStr = json_encode($dataArr, JSON_UNESCAPED_UNICODE);
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

$swooleBufferGlobal = null;
$server->on('workerStart', function ($server, $workerId) {
    $jobType = $server->taskworker ? 'Tasker' : 'Worker';
    if ($jobType == 'Worker') {
        $redisSubscribeClient = new swoole_redis;
        
        // 创建内存对象，用来保存redis订阅收到的消息
        $swooleBuffer = new swoole_buffer(BUFFER_SIZE);
        global $swooleBufferGlobal;
        $swooleBufferGlobal = $swooleBuffer;
        
        // redis订阅消息回馈
        $redisSubscribeClient->on('message', function (swoole_redis $client, $result) use ($server, $swooleBufferGlobal) {            
            if ($result[0] == 'message') {
                $swooleBufferGlobal->append($result[2].SPECIAL_SEPARATOR);
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

        $server->tick(1000, function($id) use ($swooleBufferGlobal, $server){
            $messages = $swooleBufferGlobal->substr(0, -1);
            $swooleBufferGlobal->clear();
            //$swooleBufferGlobal->recycle();
            $mseeages = trim($messages);
	
//		$message_arr = [];
//          for($i=0;$i<=5;$i++) {
//   				$message_arr[] = 'people'.$i.' say:'.$i.'_'.implode('', range(0, mt_rand(1,20)));
//            }
//			$messages = implode(SPECIAL_SEPARATOR, $message_arr);
            
            if (! empty($messages)) {
                $server->task($messages);    
            }
        });
        
        //websocket统计单用户在线时长
        // 废弃
        /**
         * 设置个每60秒执行一次的定时任务，从buffer中获取当前的用户列表，循环，判断fd是否处于活跃状态
         * 设置一个memkey useronlinetime 用于保存用户的在线时间（初始值为0），设置一个memkey usertenminutestate 用于保存用户是否已完成10分钟在线时长任务，设置一个memkey userthirtyminutestate 用于保存用户是否已完成30分钟在线时长任务
         * 如果用户还没完成10分钟在线时长任务，则给该用户的 useronlinetime+1，加1后如果 useronlinetime 的值>=10了，则标记usertenminutestate的状态为已完成
         * 如果用户已经完成10分钟在线时长任务，还没完成30分钟的任务，则给该用户的 useronlinetime+1，加1后如果 useronlinetime 的值>=30了，则标记 userthirtyminutestate 的状态为已完成
         */
        
    } else {
        
    }
});

$fdToUserinfoBufferGlobal = null;
$server->on('handshake', function(swoole_http_request $request, swoole_http_response $response) {
    global $redisclient;
    
    $userstr = $request->get['userstr'];
    if ($userinfo = decodeUser($userstr)) {
    
    /*
        global $fdToUserinfoBufferGlobal;
        $fdToUserinfoBufferGlobal = new swoole_buffer(USER_BUFFER_SIZE);
        $fd = $request->fd;
        $userMemkeyLockArr = [$fd => MEMKEY_USER_LOCK . $userinfo['area'].'_'.$userinfo['nickname']];
        $ret = swoole_serialize::pack($userMemkeyLockArr);
        var_dump(swoole_serialize::unpack($ret));
        
        $fdToUserinfoBufferGlobal->append('ljqian222');
        echo $fdToUserinfoBufferGlobal->substr(0, -1, true);
    */
    
    /*
        $redisclient->set(MEMKEY_USER_LOCK . $userinfo['area'] . '_' . $userinfo['nickname'], 0, function(){
            
            return false;
        });
    */    
        $redisclient->get(MEMKEY_USER_LOCK . $userinfo['uuid'], function(swoole_redis $client, $result) use ($userinfo, $request, $response){
            if ($result == 1) {
                
                
                $client->set(MEMKEY_USER_LOCK . $userinfo['uuid'], 0, function(){            
                    return false;
                });
                
                
                $response->status(403);
                $response->end();
                return false;
            } else {
                /**
                 * 增加单用户登录锁
                 */
                $client->set(MEMKEY_USER_LOCK . $userinfo['uuid'], 1, function(swoole_redis $client, $result) use ($userinfo, $request, $response) {
                    // 将fd与用户信息的对应关系保存起来
                    global $fdToUserinfoBufferGlobal;
                    $fdToUserinfoBuffer = new swoole_buffer(USER_BUFFER_SIZE);
                    $fdToUserinfoBufferGlobal = $fdToUserinfoBuffer;
                    $userMemkeyLockStr = trim($fdToUserinfoBufferGlobal->substr(0, -1));
                    if (empty($userMemkeyLockStr)) {
                        $fd = $request->fd;
                        $userMemkeyLockArr[$fd] = $userinfo['uuid'];
                    } else {
                        $fdToUserinfoBufferGlobal->clear();
                        $userMemkeyLockArr = swoole_serialize::unpack($userMemkeyLockStr);
                        $userMemkeyLockArr[$fd] = $userinfo['uuid'];
                    }
                    $userMemkeyLockStrNew = swoole_serialize::pack($userMemkeyLockArr);
                    $fdToUserinfoBufferGlobal->append($userMemkeyLockStrNew);
                    
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

                    // WebSocket connection to 'ws://127.0.0.1:9502/'
                    // failed: Error during WebSocket handshake:
                    // Response must not include 'Sec-WebSocket-Protocol' header if not present in request: websocket
                    if (isset($request->header['sec-websocket-protocol'])) {
                        $headers['Sec-WebSocket-Protocol'] = $request->header['sec-websocket-protocol'];
                    }

                    foreach ($headers as $key => $val) {
                        $response->header($key, $val);
                    }

                    $response->status(101);
                    $response->end();
                    //echo "connected!" . PHP_EOL;
                    
                    //var_dump($request);
                    
                    //echo '111111111';
                    
                    $client->incr(MEMKEY_WATCH_NUM, function(swoole_redis $client, $result) use ($userinfo){
                        $client->set(MEMKEY_USER_DALIY_ONLINE_STARTTIME . $userinfo['uuid'], time(), function(swoole_redis $client, $result) {});
                    });
                });
            }
        });
    } else {
        $response->status(401);
        $response->end();
        return false;
    }
    
    return true;
});

/*
$server->on('open', function ($server, $frame) {
    try {
        global $redisclient;
        $redisclient->incr(MEMKEY_WATCH_NUM, function(swoole_redis $client, $result) {
            
        });
	$server->push($frame->fd, 'hello');
    } catch (Exception $ex) {
        $server->push($frame->fd, 'error:'.$ex->getMessage());
    }
});
*/

$server->on('message', function (swoole_websocket_server $server, $frame) {
    try {
        global $redisclient;        
        list($op, $recvdata) = explode(';', $frame->data);
        if ($op == 'count') {
            $redisclient->get(MEMKEY_WATCH_NUM, function(swoole_redis $client, $result) use ($server, $frame){
                $server->push($frame->fd, 'count:'.$result);               
            });
        } elseif ($op == 'broadcast') {
            $redisclient->publish(SUBSCRIBE_CHANNEL .'_'. $server->worker_id, $recvdata, function(swoole_redis $client, $result){
                
            });
        } elseif ($op == 'buffer') {
            global $swooleBufferGlobal;
            var_dump($swooleBufferGlobal->substr(0, -1));
            echo 'workerid:'.$server->worker_id.PHP_EOL;
        }
    } catch (Exception $ex) {
        $server->push($frame->fd, 'error:'.$ex->getMessage());
    } 
});

$server->on('close', function ($serv, $fd) {    
    try {
        global $redisclient;
        
        /**
         * 当用户退出时，将单用户登录锁取消
         */
        global $fdToUserinfoBufferGlobal;
        if (isset($fdToUserinfoBufferGlobal)) {
            $userMemkeyLockStr = trim($fdToUserinfoBufferGlobal->substr(0, -1));
            $fdToUserinfoBufferGlobal->clear();
            
            $userMemkeyLockArr = swoole_serialize::unpack($userMemkeyLockStr);
            var_dump($userMemkeyLockArr);            

            if (isset($userMemkeyLockArr[$fd])) {
                $useruuid = $userMemkeyLockArr[$fd];
                unset($userMemkeyLockArr[$fd]);
                $memkey = MEMKEY_USER_LOCK . $useruuid;
                
                $userMemkeyLockStrNew = swoole_serialize::pack($userMemkeyLockArr);
                $fdToUserinfoBufferGlobal->append($userMemkeyLockStrNew);
                
                $redisclient->set($memkey, 0, function(swoole_redis $client, $result) use ($useruuid){
                    $client->get(MEMKEY_USER_DALIY_ONLINE_STARTTIME . $useruuid, function(swoole_redis $client, $result) use ($useruuid) {
                        $starttime = (int)$result;
                        $onlineminute = ceil((time()-$starttime)/60);
                        
                        //echo MEMKEY_USER_DALIY_ONLINE_LIVETIME .date('Ymd'). $useruuid.PHP_EOL;
                        $client->incrBy(MEMKEY_USER_DALIY_ONLINE_LIVETIME .date('Ymd').'_'. $useruuid, $onlineminute, function(swoole_redis $client, $result) {
                            //echo $result.PHP_EOL;
                            $client->decr(MEMKEY_WATCH_NUM, function(swoole_redis $client, $result) {});    
                        });
                    });
                });
            } else {
                $redisclient->decr(MEMKEY_WATCH_NUM, function(swoole_redis $client, $result) {});    
            }
        } else {
            $redisclient->decr(MEMKEY_WATCH_NUM, function(swoole_redis $client, $result) {});
        }
    } catch (Exception $ex) {
        $serv->push($fd, 'error:'.$ex->getMessage());
    }    
});

$server->on('workerStop', function($server, $worker_id) {
    global $redisclient;
    $redisclient->close();
    
    global $redisSubscribeClient;
    $redisSubscribeClient->close();
    
    global $swooleBufferGlobal;
    $swooleBufferGlobal->recycle();
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
        
    $ret = openssl_decrypt($encryptedText, 'AES-128-CBC', 'afcfc7966b28b6df', OPENSSL_RAW_DATA, '2b41a307496edd66');        
    if (empty($ret)) {
        return false;
    }
    
    $userinfo = json_decode($ret, true);
    if (empty($userinfo) || empty($userinfo['area']) || empty($userinfo['nickname']) || empty($userinfo['uuid'])) {
        return false;
    }
    return $userinfo;
}
