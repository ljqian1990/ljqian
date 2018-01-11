<?php
$client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
$account = 'ljqian22';
$client->on("connect", function(swoole_client $cli) use($account) {
    $cli->send("GET /getinfo?account={$account}&all=");
});
$ret = '';
$client->on("receive", function(swoole_client $cli, $data) use (&$ret){
//    echo "Receive: $data";
    $ret = $data;
//    $cli->send(str_repeat('A', 100)."\n");
//    sleep(1);
});
echo $ret;
$client->on("error", function(swoole_client $cli){
    echo "error\n";
});
$client->on("close", function(swoole_client $cli){
    echo "Connection close\n";
});
$client->connect('192.168.12.132', 50001);
