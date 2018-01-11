<?php

//����Server���󣬼��� 127.0.0.1:9501�˿�
$serv = new swoole_server("0.0.0.0", 60000, SWOOLE_PROCESS); 

$serv->set(['worker_num'=>1, 'max_request'=>3]);

//�������ӽ����¼�
$serv->on('connect', function ($serv, $fd) {  
//    echo "Client: Connect.\n";
});

$serv->on('WorkerStart', function($serv, $worker_id) {
	echo $worker_id;
	require_once '/home/qianlijia/swoole/test.php';
});

//�������ݽ����¼�
$serv->on('receive', function ($serv, $fd, $from_id, $data) {
	var_dump($_SERVER);
//    $serv->send($fd, "Server: ".$data);
	$newdata = Test::test11($data);
    $serv->send($fd, $newdata);
});

//�������ӹر��¼�
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


//����������
$serv->start(); 


/*
$http_server = new swoole_http_server('0.0.0.0',60030); 
$http_server->set(array('daemonize'=> false));
$http_server->on('request',function($serv, $fd, $data) {
	echo $data."\n";
});
//......���ø����ص�......
//�����һ��tcp�˿ڣ����⿪��tcp���񣬲�����tcp�������Ļص�
$tcp_server = $http_server->addListener('0.0.0.0', 60031, SWOOLE_SOCK_TCP);
//Ĭ���¼����Ķ˿� 9999 ��̳��������������ã�Ҳ�� Http Э��
//��Ҫ���� set ����������������������
$tcp_server->set(array());
$tcp_server->on("receive", function ($serv, $fd, $threadId, $data) {
    echo $data;
});
*/
