<?php
$cli = new swoole_http_client('192.168.39.4', 41000);
$cli->on('message', function($_cli, $frame) {
	if($frame->finish) {
		echo $frame->data;
	}
});

$cli->upgrade('/', function($cli) {
//    var_dump($cli->body);
    $cli->push('hello world');
});

