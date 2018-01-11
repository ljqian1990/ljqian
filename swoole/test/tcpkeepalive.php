<?php
//创建Server对象，监听 127.0.0.1:9501端口
$serv = new swoole_server("0.0.0.0", 60040); 

$serv->set(['heartbeat_check_interval'=>5, 'heartbeat_idle_time'=>10 ,'open_tcp_keepalive'=>true,'tcp_keepidle'=>5, 'tcp_keepcount'=>5, 'tcp_keepinterval'=>5]);

//监听连接进入事件
$serv->on('connect', function ($serv, $fd) {  
    echo "Client: Connect.\n";
});

//监听数据接收事件
$serv->on('receive', function ($serv, $fd, $from_id, $data) {
    $serv->send($fd, "Server: ".$data);
});

//监听连接关闭事件
$serv->on('close', function ($serv, $fd) {
    echo "Client: Close.\n";
});

//启动服务器
$serv->start(); 
