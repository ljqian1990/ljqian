<?php
//for ($i=0;$i<=50;$i++) {
$cli = new swoole_http_client('172.30.104.238', 41010);
$cli->on('message', function($_cli, $frame) {
	if($frame->finish) {
		echo $frame->data;
	}
});

$cli->upgrade('/', function($cli) {
//    var_dump($cli->body);
    $cli->push('broadcast;hello world');
});
//}
