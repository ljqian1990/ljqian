<?php
require './DB.class.php';
$config = ['master'=>['host'=>'localhost','port'=>'3306','user'=>'root','passwd'=>'','database'=>'ljqian','charset'=>'utf8','pconnect'=>false], 'slave'=>[['host'=>'localhost','port'=>'3306','user'=>'root','passwd'=>'','database'=>'ljqian','charset'=>'utf8','pconnect'=>false]]];
$db = new DB($config);
// $ret = $db->select('test', '*', ['where'=>['id'=>1, 'name'=>'ljqian', 'id'=>['or'=>['in'=>[1,2]]]]]);
// $ret = $db->select('test', '*', ['where'=>['id'=>['in'=>[1,2]]]]);
// $ret = $db->select('test', '*', ['where'=>['id'=>['or'=>1]]]);
// $ret = $db->select('test', '*', ['where'=>['id'=>1]]);
//$ret = $db->select('test', '*', ['where'=>['id'=>['or'=>['in'=>[1,2]]], 'name'=>['or'=>['like'=>'%2%']], 'time1'=>['like'=>'%2017-07-26%'], 'time2'=>['or'=>'2017-07-26 17:17:59']]]);

$db->decr('test', 'num', 10, ['id'=>1]);
// var_dump($ret);exit;


