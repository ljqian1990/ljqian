<?php

//创建Server对象，监听 127.0.0.1:9501端口
$serv = new swoole_server("0.0.0.0", 60000, SWOOLE_PROCESS); 

$serv->set(['worker_num'=>1, 'max_request'=>3]);

//监听连接进入事件
$serv->on('connect', function ($serv, $fd) {  
//    echo "Client: Connect.\n";
});

$serv->on('WorkerStart', function($serv, $worker_id) {
	echo $worker_id;
	require_once '/home/qianlijia/swoole/test.php';
});

//监听数据接收事件
$serv->on('receive', function ($serv, $fd, $from_id, $data) {
	var_dump($_SERVER);
//    $serv->send($fd, "Server: ".$data);
	$newdata = Test::test11($data);
    $serv->send($fd, $newdata);
});

//监听连接关闭事件
$serv->on('close', function ($serv, $fd) {
//    echo "Client: Close.\n";
});

$port = $serv->addListener('0.0.0.0', 60001, SWOOLE_SOCK_TCP);
//var_dump($port);
$port->set([]);
$port->on('receive',function($serv, $fd, $threadId, $data) {
	$info = $serv->connection_info($fd, $threadId);
	var_dump($info);
	echo $data;
});


//启动服务器
$serv->start(); 


/*
$http_server = new swoole_http_server('0.0.0.0',60030); 
$http_server->set(array('daemonize'=> false));
$http_server->on('request',function($serv, $fd, $data) {
	echo $data."\n";
});
//......设置各个回调......
//多监听一个tcp端口，对外开启tcp服务，并设置tcp服务器的回调
$tcp_server = $http_server->addListener('0.0.0.0', 60031, SWOOLE_SOCK_TCP);
//默认新监听的端口 9999 会继承主服务器的设置，也是 Http 协议
//需要调用 set 方法覆盖主服务器的设置
$tcp_server->set(array());
$tcp_server->on("receive", function ($serv, $fd, $threadId, $data) {
    echo $data;
});
*/
