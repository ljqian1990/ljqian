<?php
$workers = [];

$table = new swoole_table(1024);
$table->column('num1', swoole_table::TYPE_INT , 8);
$table->column('num2', swoole_table::TYPE_INT , 8);
$table->create();
$table->set('number', ['num1'=>0, 'num2'=>0]);

$process1 = new swoole_process(function($worker) use ($table){
	while(1) {
//		sleep(1);
		$recv = $worker->pop();
		$table->incr('number', 'num1');
		echo 'number1:'.$table->get('number', 'num1')."\r\n";
    	echo $recv."\r\n";
		if ($table->get('number', 'num1') >=3) {
			$worker->exit(0);
		}
//		sleep(1);
	}
//    $worker->exit(0);
}, false, false);
$process1->useQueue();
$pid1 = $process1->start();
$workers[$pid1] = $process1;

$process2 = new swoole_process(function($worker) use ($table){
	while(1) {
		//sleep(1);
	    $recv = $worker->pop();
		$table->incr('number', 'num2');
		echo 'number2:'.$table->get('number', 'num2'). "\r\n";
    	echo $recv."\r\n";
//		var_dump($table->get('number', 'num2'));
		if ($table->get('number', 'num2')>=5) {
			$worker->exit(0);
		}
//		sleep(1);
	}
//    sleep(5);
//    $worker->exit(0);
}, false, false);
$process2->useQueue();
$pid2 = $process2->start();
$workers[$pid2] = $process2;

$process2->push('test11');
$process2->push('test12');
$process2->push('test21');
$process2->push('test22');
$process2->push('test31');
$process2->push('test32');
$process2->push('test41');
$process2->push('test42');
$process2->push('test51');
$process2->push('test52');

$ret = swoole_process::wait();
//var_dump($ret);
