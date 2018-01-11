<?php
$ret = file_get_contents("http://www.quanmin.tv/json/rooms/8190020/info4.json?_t=201612271610");
$arr = json_decode($ret, true);
var_dump($arr);exit;
$arr['live']['ws']['hls'][0]['src'];