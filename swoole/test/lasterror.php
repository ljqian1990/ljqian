<?php
//创建Server对象，监听 127.0.0.1:9501端口
$serv = new swoole_server("0.0.0.0", 60010); 

$serv->set(['worker_num'=>1, 'max_request'=>3, 'dispatch_model'=>3]);

//监听连接进入事件
$serv->on('connect', function ($serv, $fd) {  
    echo "Client: Connect.\n";
});

//监听数据接收事件
$serv->on('receive', function ($serv, $fd, $from_id, $data) {
	$serv->tick(1000, function($tid) use ($serv, $fd) {				
		$errno = $serv->getLastError();
		//echo $errno;
		if ($errno === 0) {		
			$serv->send($fd, "Server: hello");
		} else {
			echo 'connection closed!';
//			$serv->close($fd);
			$serv->clearTimer($tid);
		}
		//$serv->send($fd, "Server: hello");
	});
    //$serv->send($fd, "Server: ".$data);
});

//监听连接关闭事件
$serv->on('close', function ($serv, $fd) {
    echo "Client: Close.\n";
});

//启动服务器
$serv->start(); 
