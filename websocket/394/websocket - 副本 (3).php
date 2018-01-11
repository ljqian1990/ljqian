<?php
const REDIS_HOST = 'w2.dev.ztgame.com';
const REDIS_PORT = 6379;

const MEMKEY_WATCH_NUM = 'jl_act_match_ws_num';
const SUBSCRIBE_CHANNEL = 'msg_0';

/*
function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    throw new Exception("$errno,$errstr", 1);
}
*/
//set_error_handler('myErrorHandler');


$server = new swoole_websocket_server("0.0.0.0", 41000);
$server->set(['worker_num'=>4, 'max_request'=>0, 'max_conn'=>20000, 'daemonize'=>0]);

$redisclient = null;
$redisSubscribeClient = null;

/*
$server->on('start', function(swoole_server $server) {
    
    $redisSubscribeClient = new swoole_redis;
    $redisSubscribeClient->on('message', function (swoole_redis $client, $result) use ($server) {
        //echo 'get message success'.PHP_EOL;
        if ($result[0] == 'message') {
            //echo $server->worker_id.PHP_EOL;
            //echo 'count:'.count($server->connections).PHP_EOL;
            foreach($server->connections as $fd) {
                
                $server->push($fd, $result[2]);
            }
        }
    });
});
*/

$server->on('workerStart', function ($server, $workerId) {
    $redisSubscribeClient = new swoole_redis;
    
    $redisSubscribeClient->on('message', function (swoole_redis $client, $result) use ($server) {
        //echo 'get message success'.PHP_EOL;
        if ($result[0] == 'message') {
            //echo $server->worker_id.PHP_EOL;
            //echo 'count:'.count($server->connections).PHP_EOL;
            foreach($server->connections as $fd) {
                
                $server->push($fd, $result[2]);
            }
        }
    });
    
    $redisSubscribeClient->connect(REDIS_HOST, REDIS_PORT, function (swoole_redis $client, $result) use ($server) {
        $client->set(MEMKEY_WATCH_NUM, 0, function(swoole_redis $client, $result) use ($server){       
            $client->subscribe(SUBSCRIBE_CHANNEL.'_'. $server->worker_id);
        });
    });
        
    $redisCommonClient = new swoole_redis;
    $redisCommonClient->connect(REDIS_HOST, REDIS_PORT, function (swoole_redis $client, $result) {
        global $redisclient;
        $redisclient = $client;
    });    
});

$server->on('open', function ($server, $frame) {
    try {
        global $redisclient;
        $redisclient->incr(MEMKEY_WATCH_NUM, function(swoole_redis $client, $result) {
            
        });
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
                //echo 'publish success'.PHP_EOL;
            });
        }    
    } catch (Exception $ex) {
        $server->push($frame->fd, 'error:'.$ex->getMessage());
    } 
});

$server->on('close', function ($serv, $fd) {
    try {
        global $redisclient;
        
        $redisclient->decr(MEMKEY_WATCH_NUM, function(swoole_redis $client, $result) {
            //$client->close();
        });    
    } catch (Exception $ex) {
        $serv->push($fd, 'error:'.$ex->getMessage());
    }    
});

$server->start();