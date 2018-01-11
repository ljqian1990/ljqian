<?php
$server = new swoole_websocket_server("0.0.0.0", 41001);

$server->set(['worker_num'=>1]);

$server->on('workerStart', function ($server, $workerId) {
    $client = new swoole_redis;
    $client->on('message', function (swoole_redis $client, $result) use ($server) {
        echo 'get message success'.PHP_EOL;
        if ($result[0] == 'message') {
            foreach($server->connections as $fd) {
                $server->push($fd, $result[2]);
            }
        }
    });
    $client->connect('w2.dev.ztgame.com', 6379, function (swoole_redis $client, $result) {
        $client->subscribe('msg_0');
    });
});

$server->on('open', function ($server, $request) {

});

$server->on('message', function (swoole_websocket_server $server, $frame) {
    
    //$server->push($frame->fd, $frame->data);
    
    $client = new swoole_redis;
    $client->connect('w2.dev.ztgame.com', 6379, function (swoole_redis $client, $result) use($frame){
        $client->publish('msg_0', $frame->data, function(swoole_redis $client, $result){
            echo 'publish success'.PHP_EOL;
        });
    });
    
});

$server->on('close', function ($serv, $fd) {

});

$server->start();
