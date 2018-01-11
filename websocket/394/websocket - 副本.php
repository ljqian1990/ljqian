<?php
$redisclient = new swoole_redis;

$server = new swoole_websocket_server("0.0.0.0", 41000);

$server->set(['worker_num'=>2, 'max_request'=>0, 'max_conn'=>10000]);

/*
$server->on('start', function(swoole_server $server) {
    echo 'start'.PHP_EOL;
    
    global $redisclient;
    
    $redisclient->on('message', function (swoole_redis $client, $result) use ($server){
        echo '4'.PHP_EOL;
        if ($result[0] == 'message') {
            foreach($server->connections as $fd) {
                $server->push($fd, $result[2]);
            }
        }
    });
});*/



$server->on('open', function (swoole_websocket_server $server, $request) use ($redisclient){

    //global $redisclient;

    $redisclient->connect('w2.dev.ztgame.com', 6379, function (swoole_redis $client, $result) {
        $client->incr('ljqian_websocket_num', function(swoole_redis $client, $result) {
            echo 'incr success'.PHP_EOL;
            $client->subscribe('msg_ljqian');
            echo 'subscribe success'.PHP_EOL;
        });
    });

});

$server->on('message', function (swoole_websocket_server $server, $frame) use ($redisclient){
    
    //global $redisclient;
    
	list($op, $recvdata) = explode(';', $frame->data);
	if ($op == 'count') {	
        $redisclient->connect('w2.dev.ztgame.com', 6379, function (swoole_redis $client, $result) use ($server, $frame) {
            $client->get('ljqian_websocket_num', function(swoole_redis $client, $result) use ($server, $frame) {                
                $server->push($frame->fd, 'count:'.$result);
            });
        });
	} elseif ($op == 'broadcast') {        
        echo '1'.PHP_EOL;
        $redisclient->connect('w2.dev.ztgame.com', 6379, function (swoole_redis $client, $result) use ($server, $frame, $recvdata) {        
        echo '2'.PHP_EOL;
            $client->publish('msg_ljqian', $recvdata, function(swoole_redis $client, $result) use ($server, $frame, $recvdata) { 
            echo '3'.PHP_EOL;
                //$server->push($frame->fd, $recvdata);
                //$server->push($frame->fd, 'success');
            });
        });
	}
});

$server->on('close', function ($ser, $fd) use ($redisclient){
    
    //global $redisclient;
    
    $redisclient->connect('w2.dev.ztgame.com', 6379, function (swoole_redis $client, $result) {                
        $client->decr('ljqian_websocket_num', function(swoole_redis $client, $result) {
            echo 'decr success'.PHP_EOL;
            //$client->unsubscribe('msg_ljqian');
        });
        
        $client->close();
    });
});

$server->start();
