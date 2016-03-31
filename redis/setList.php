<?php
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$llen = $redis->lLen('list');
if(!empty($llen)){
	for($i=0; $i<$llen; $i++){
		$redis->rPop('list');
	}
}

for ($i=1; $i<=6; $i++){
	$redis->lPush('list', $i);	
}

echo $redis->lLen('list');

for ($i=0; $i<$redis->scard('uid'); $i++){
	$redis->sPop('uid');
}


$redis->set('llen', 0);