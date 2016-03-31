<?php
//����Server���󣬼��� 127.0.0.1:9501�˿�
$serv = new swoole_server("127.0.0.1", 9501);

//�������ӽ����¼�
$serv->on('connect', function ($serv, $fd) {
	echo "Client: Connect.\n";
});

//�������ݷ����¼�
$serv->on('receive', function ($serv, $fd, $from_id, $data) {
	$serv->send($fd, "Server: ".$data);
});

//�������ӹر��¼�
$serv->on('close', function ($serv, $fd) {
	echo "Client: Close.\n";
});

//����������
$serv->start();