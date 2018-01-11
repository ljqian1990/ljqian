<?php
$server = new swoole_server('0.0.0.0', 60002);

$process = new swoole_process(function($process) use ($server) {//echo 1;
    while (true) {
        $msg = $process->read();
        foreach($server->connections as $conn) {
            $server->send($conn, $msg);
        }
    }
});

$server->addProcess($process);

$jianguan = new swoole_process(function($process) use ($server) {//var_dump($server);
    while (true) {
        $msg = $process->read();
        foreach ($server->connections as $conn) {
            $server->send($conn, 'hello'.$msg);
        }
//        if ($msg == 'ljqian') {
  //          $server->sendMessage(json_encode($server), $server->worker_id);
    //    }
    }
});

$server->addProcess($jianguan);

$server->on('receive', function ($serv, $fd, $from_id, $data) use ($process, $jianguan) {
    //群发收到的消息
    $process->write($data);
    $jianguan->write($data);
    $serv->tick(1000, function() use ($serv, $fd) {
        $serv->send($fd, "hello world");
    });
});

$server->on('pipeMessage', function($serv, $src_worker_id, $data) {
    echo "$src_worker_id,{$serv->worker_id},$data\n";
});

$server->start();
