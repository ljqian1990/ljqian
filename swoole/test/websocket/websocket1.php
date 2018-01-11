<?php
$redisclient = new swoole_redis;

$server = new swoole_websocket_server("0.0.0.0", 41001);

$redisclient->on('message', function (swoole_redis $client, $result) use ($server){        
    $more = false;
    if (!$more && $result[0] == 'message') {
        foreach($server->connections as $fd) {
            $server->push($fd, $result[2]);
        }
        $more = true;
    }
});


$server->on('open', function (swoole_websocket_server $server, $request) use ($redisclient){    
    $redisclient->connect('w2.dev.ztgame.com', 6379, function (swoole_redis $client, $result) {
        $client->incr('ljqian_websocket_num', function(swoole_redis $client, $result) {
            echo 'incr success'.PHP_EOL;
            $client->subscribe('msg_ljqian');
            echo 'subscribe success'.PHP_EOL;
        });
    });
});

$server->on('message', function (swoole_websocket_server $server, $frame) use($redisclient){
	list($op, $recvdata) = explode(';', $frame->data);
	if ($op == 'count') {	
        $redisclient->connect('w2.dev.ztgame.com', 6379, function (swoole_redis $client, $result) use ($server, $frame) {
            $client->get('ljqian_websocket_num', function(swoole_redis $client, $result) use ($server, $frame) {                
                $server->push($frame->fd, 'count:'.$result);
            });
        });
	} elseif ($op == 'broadcast') {        
        $redisclient->connect('w2.dev.ztgame.com', 6379, function (swoole_redis $client, $result) use ($server, $frame, $recvdata) {        
            $client->publish('msg_ljqian', $recvdata, function(swoole_redis $client, $result) use ($server, $frame) {        
                //$server->push($frame->fd, 'success');
            });
        });
	}
});

$server->on('close', function ($ser, $fd) use ($redisclient){
    $redisclient->connect('w2.dev.ztgame.com', 6379, function (swoole_redis $client, $result) {                
        $client->decr('ljqian_websocket_num', function(swoole_redis $client, $result) {
            echo 'decr success'.PHP_EOL;
            //$client->unsubscribe('msg_ljqian');
        });
        
        $client->close();
    });
});

$server->start();
