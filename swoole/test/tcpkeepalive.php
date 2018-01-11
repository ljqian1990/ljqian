<?php
//����Server���󣬼��� 127.0.0.1:9501�˿�
$serv = new swoole_server("0.0.0.0", 60040); 

$serv->set(['heartbeat_check_interval'=>5, 'heartbeat_idle_time'=>10 ,'open_tcp_keepalive'=>true,'tcp_keepidle'=>5, 'tcp_keepcount'=>5, 'tcp_keepinterval'=>5]);

//�������ӽ����¼�
$serv->on('connect', function ($serv, $fd) {  
    echo "Client: Connect.\n";
});

//�������ݽ����¼�
$serv->on('receive', function ($serv, $fd, $from_id, $data) {
    $serv->send($fd, "Server: ".$data);
});

//�������ӹر��¼�
$serv->on('close', function ($serv, $fd) {
    echo "Client: Close.\n";
});

//����������
$serv->start(); 
