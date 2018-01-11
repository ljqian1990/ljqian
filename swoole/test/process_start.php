<?php

$table = new swoole_table(1024);
$table->column('id', swoole_table::TYPE_STRING , 100);
$table->create();

$workers = [];
$worker_num = 3;//创建的进程数

for($i=0;$i<$worker_num ; $i++){
    $process = new swoole_process('process');
    $pid = $process->start();
    $workers[$pid] = $process;
}

foreach($workers as $process){
    //子进程也会包含此事件
    swoole_event_add($process->pipe, function ($pipe) use($process, $table){
        //$table->set('ljqian', ['id'=>]);
        $data = $process->read();
        if(!$id = $table->get('ljqian', 'id')) {
	    $id = 0;
        }
//var_dump($id);
	$newid = $id.','.$data;
        $table->set('ljqian', ['id'=>$newid]);
//        echo "RECV: " . $data.PHP_EOL;
	echo "newid:".$newid.PHP_EOL;
    });
}

function process(swoole_process $process){// 第一个处理
    $process->write($process->pid);
//    echo $process->pid,"\t",$process->callback .PHP_EOL;
}
