<?php
$serv = new swoole_server("0.0.0.0", 60005);
$serv->set(array(
    'worker_num' => 2,
    'task_worker_num' => 2,
));
$serv->on('pipeMessage', function($serv, $src_worker_id, $data) {
    echo "#{$serv->worker_id} message from #$src_worker_id: $data\n";
});
$serv->on('task', function ($serv, $task_id, $from_id, $data){
   // var_dump($task_id, $from_id, $data);
	return $data;
//    foreach ($serv->connections as $conn) {
//        $serv->send($conn, 'guanggao'.$data."\n");
//    }
});
$serv->on('finish', function ($serv, $task_id, $data){

    foreach ($serv->connections as $conn) {
        $serv->send($conn, 'guanggao'.$data."\n");
    }
//    echo "finish\n";
});
$serv->on('receive', function (swoole_server $serv, $fd, $from_id, $data) {
   // var_dump($serv->stats());
    if (trim($data) == 'task')
    {
        $serv->task("async task coming");
    }
    else
    {
        $worker_id = 1 - $serv->worker_id;
	echo $worker_id;
        $serv->sendMessage("hello task process", $worker_id);
    }
});

$serv->start();

//var_dump($serv->stats());
