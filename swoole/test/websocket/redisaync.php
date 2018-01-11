<?php
$client = new swoole_redis;
$client->connect('w2.dev.ztgame.com', 6379, function (swoole_redis $client, $result) {
    if ($result === false) {
        echo "connect to redis server failed.\n";
        return false;
    }
    $client->set('key', 'swoole', function (swoole_redis $client, $result) {
        var_dump($result);
    });
});
