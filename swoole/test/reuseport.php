<?php
$serv = new swoole_server("0.0.0.0", 60020);
$serv->set(array(
    'worker_num' => 1,    //开启两个worker进程
    'max_request' => 3,   //每个worker进程max request设置为3次
	'tcp_defer_accept' => 5,
	'enable_reuse_port' => true
));
//监听数据接收事件
$serv->on('receive', function ($serv, $fd, $from_id, $data) {
    $serv->send($fd, "Server: ".$data.'#fd:'.$fd);
});
//启动服务器
$serv->start();
