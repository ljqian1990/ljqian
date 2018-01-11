<?php
$serv = new swoole_server("0.0.0.0", 60020);
$serv->set(array(
    'worker_num' => 1,    //��������worker����
    'max_request' => 3,   //ÿ��worker����max request����Ϊ3��
	'tcp_defer_accept' => 5,
	'enable_reuse_port' => true
));
//�������ݽ����¼�
$serv->on('receive', function ($serv, $fd, $from_id, $data) {
    $serv->send($fd, "Server: ".$data.'#fd:'.$fd);
});
//����������
$serv->start();
