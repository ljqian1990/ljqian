<?php
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$llen = $redis->lLen('list');
if(!empty($llen)){
	for ($i=0; $i<$llen; $i++){
		echo $redis->lindex('list', $i)."<br/>";
	}
}else {
	echo 'empty';
}

echo $redis->get('llen');
