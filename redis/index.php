<?php
include_once dirname(dirname(__FILE__)).'\pdo.php';

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$member = rand(1, 100);

$uid = $_GET['uid'];

if($redis->setnx('uid_'.$uid, 1)){
	$redis->expire('uid_'.$uid, 3);
}else{
	return false;
}

$count = $redis->lPop('list');
if(!$count){
	return false;
}



$dd = date('Y-m-d H:i:s', time());
$stmt = Database::prepare ( "insert into ljqian(`name`,`tel`,`sex`, `dd`)values('$uid', '189455', 1, '$dd')" );
$stmt->execute ();



