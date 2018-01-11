<?php
$client = new swoole_redis;
$client->on('message', function (swoole_redis $client, $result) {
    var_dump($result);
    static $more = false;
    if (!$more and $result[0] == 'message')
    {
        echo "subscribe new channel\n";
        $client->subscribe('msg_1', 'msg_2');
        $client->unsubscribe('msg_0');
        $more = true;
    }
});
$client->connect('w2.dev.ztgame.com', 6379, function (swoole_redis $client, $result) {
    echo "connect\n";
    $client->subscribe('msg_0');
//	$client->close();
});


/*
$client->connect('w2.dev.ztgame.com', 6379, function (swoole_redis $client, $result) {
	$memkey = 'ljqian_test_async_redis';
	$client->set($memkey, 'ljqian', function(swoole_redis $client, $result) use ($memkey){
		$client->get($memkey, function(swoole_redis $client, $result){
			var_dump($result);exit;
		});
	});
});
*/
/*
$client->connect('w2.dev.ztgame.com', 6379, function(swoole_redis $client, $result){
	$client->publish('msg_0', 'ljqiantest', function(swoole_redis $client, $result){
		var_dump($result);
	});
});
*/
