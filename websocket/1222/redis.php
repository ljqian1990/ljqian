<?php
ini_set('memory_limit','2048M');
$redis = new swoole_redis;
$redis->connect('w1.dev.ztgame.com', 6379, function(swoole_redis $redis, $result) {
    for ($i=0; $i<=300000; $i++) {
        $redis->set('ljqian'.$i, $i, function(swoole_redis $redis, $result) use ($i){
            $redis->get('ljqian'.$i, function(swoole_redis $redis, $result) use ($i){
                if ($result != $i) {
                    echo 'error:'.$i.PHP_EOL;
                } else {
                    echo $i.PHP_EOL;
                }
            });
        });
    }
    
});

echo 'OK';