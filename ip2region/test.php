<?php
include_once(dirname(__FILE__).'/vendor/autoload.php');

$ip2region = new Ip2Region();

$ip = '180.168.126.183';

$info = $ip2region->btreeSearch($ip);
var_dump($info);exit;