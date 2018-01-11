<?php
$redis = new Swoole\Coroutine\Redis();
$redis->connect('w2.dev.ztgame.com', 6379);
while (true) {
    $val = $redis->subscribe(['pubsub']);
    //订阅的channel，以第一次调用subscribe时的channel为准，后续的subscribe调用是为了收取Redis Server的回包
    //如果需要改变订阅的channel，请close掉连接，再调用subscribe
    var_dump($val);
}
