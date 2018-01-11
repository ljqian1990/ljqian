<?php
const REDIS_HOST = 'w2.dev.ztgame.com';
const REDIS_PORT = 6379;

const MEMKEY_WATCH_NUM = 'jl_act_match_ws_num';
const SUBSCRIBE_CHANNEL = 'msg_0';

const BUFFER_SIZE = 8192;

/*
function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    throw new Exception("$errno,$errstr", 1);
}
*/
//set_error_handler('myErrorHandler');


$server = new swoole_websocket_server("0.0.0.0", 41010);
$server->set(['worker_num'=>16, 'max_request'=>0, 'max_conn'=>20000, 'daemonize'=>0, 'task_worker_num'=>20]);

$redisclient = null;
$redisSubscribeClient = null;



$server->on('task', function($server, $task_id, $from_id, $data){


    foreach($server->connections as $fd) {                

	if ($server->exist($fd)) {

            $server->push($fd, $data);
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
                $swooleBufferGlobal->append($result[2].';');
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
            $messages = $swooleBufferGlobal->substr(0, -1, true);
            $swooleBufferGlobal->clear();
            //$swooleBufferGlobal->recycle();
            $mseeages = trim($messages);
            
            if (! empty($messages)) {
                $server->task($messages);    
            }
        });
    } else {
        
    }
});

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
            var_dump($swooleBufferGlobal->substr(0, -1, true));
            echo 'workerid:'.$server->worker_id.PHP_EOL;
        }
    } catch (Exception $ex) {
        $server->push($frame->fd, 'error:'.$ex->getMessage());
    } 
});

$server->on('close', function ($serv, $fd) {
    try {
        global $redisclient;
        
        $redisclient->decr(MEMKEY_WATCH_NUM, function(swoole_redis $client, $result) {
            
        });    
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
