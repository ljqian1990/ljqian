<?php
$cli = new swoole_http_client('192.168.39.4', 40100);
$cli->on('message', function($_cli, $frame) {
//    var_dump($frame);
});

$cli->upgrade('/', function($cli) {
    var_dump($cli->body);
    $cli->push('hello world');
});

