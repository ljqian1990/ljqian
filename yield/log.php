<?php
/*
function logger($fileName) {
    $fileHandle = fopen($fileName, 'a');
    while (true) {
        fwrite($fileHandle, yield . "\n");
    }
}
 
$logger = logger(__DIR__ . '/log');
$logger->send('Foo');
$logger->send('Bar');
*/

/*
class Logger
{
	public function test()
	{
		var_dump(yield);exit;
	}
}

$log = new Logger();
$obj = $log->test();
$obj->send('xxx');
*/

/*
function gen() {
    $ret = (yield 'yield1');
    var_dump($ret . ' var_dump1');
    $ret = (yield 'yield2');
    var_dump($ret . ' var_dump2');
	yield 'yield3';
}
 
$gen = gen();
var_dump($gen->current());
var_dump($gen->send('ret1')); // string(4) "ret1"   (the first var_dump in gen)
                              // string(6) "yield2" (the var_dump of the ->send() return value)
var_dump($gen->send('ret2'));
*/

/*
function gen()
{
	$ret = (yield 'yield1');
	var_dump(yield);
	yield 'yield2';
	var_dump(yield);
	yield 'yield3';
	var_dump(yield);
}
$gen = gen();
var_dump($gen->current());
var_dump($gen->send('test1'));// null
var_dump($gen->send('test2'));// test2 yield2
var_dump($gen->send('test3'));// null
var_dump($gen->send('test4'));// test4 yield3
var_dump($gen->send('test5'));// null
var_dump($gen->send('test6'));// test6
var_dump($gen->send('test'));// null
var_dump($gen->send('test'));
var_dump($gen->send('test'));
var_dump($gen->send('test'));
*/

/*
function gen()
{
	yield 'test1';
	yield 'test2';
}

$gen = gen();
var_dump($gen->send('ljqian'));
*/

function gen()
{
	echo 1;
	yield;
	echo 2;
	yield;
	echo 3;
	yield;
	echo 4;
	yield;
	echo 5;
	yield;
}
$gen = gen();
var_dump($gen->current());
var_dump($gen->send(null));
var_dump($gen->send(null));
var_dump($gen->send(null));
var_dump($gen->send(null));
var_dump($gen->send(null));

