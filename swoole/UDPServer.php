<?php
//����Server���󣬼��� 127.0.0.1:9502�˿ڣ�����ΪSWOOLE_SOCK_UDP
$serv = new swoole_server("127.0.0.1", 9502, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);

//�������ݷ����¼�
$serv->on('Packet', function ($serv, $fd, $data, $clientInfo) {
	$serv->send($fd, "Server: ".$data);
	var_dump($clientInfo);
});

//����������
$serv->start();