<?php
class Test
{
	public function __construct()
	{
		echo 2;
	}
	
	public function __invoke()
	{
		echo 1;
	}
}
function test1()
{
	return new Test();
}
function test2()
{
	$ret = (yield test1());	
}
var_dump(test2()->current());
exit;

function xrange($start, $end, $step = 1) {
    for ($i = $start; $i <= $end; $i += $step) {
        yield $i;
    }
}

function crange($start, $end, $step = 1) {
    $ret = [];
    for ($i = $start; $i <= $end; $i += $step) {
        $ret[] = $i;
    }
    return $ret;
}

var_dump(xrange(1,100000));exit;

// foreach (xrange(1, 1000000) as $num) {
//     echo $num, "\n";
// }