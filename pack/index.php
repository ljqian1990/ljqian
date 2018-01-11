<?php

/**
 * 将巨人通行证帐号、角色所在区号、角色所在区名称、角色的等级、角色ID、角色名称、当前时间 通过","分割组成字符串。例如：string = xiaojunbin123,12,双线1区,2,170209810,鼠标test,2013-12-25 18:08:54
* 在string后面追加",0123456"子串后进行md5加密，并取前10位字符作为校验码。注意，此时string的值不变！md5_value = substr(md5(string+",0123456"), 0, 10);其中substr(value, 0, 10)表示截取value字符串的前10位字符
* 将string打包成一个十六进制，高四位在前的串。hex = unpack('H*', string);hex_string = hex[1];注意：unpack在php中返回的是数组类型，所以这边取hex[1]的值为打包好的字符串，其他语言根据实际情况取值，最终目的是取十六进制串。
* 将hex_string和md5_value组合起来的值传递给web端。return hex_string+md5_value;
*
* 其他
* 如果客户端是GBK格式的编码，请告知一声即可
*/

/**
 * 如下是用php写的加解密过程
*/
$endtime = microtime(true);
echo $endtime;exit;
echo strtotime('2016-10-19 11:00:00');exit;
/**
 * 用php实现的加密过程
 */
$arr = ['ljqian1990', '12', '双线1区', '100', '170209811', 'ljqiantest', '2015-12-25 18:08:54'];
$str = implode(',', $arr);
$str = iconv('UTF-8', 'GBK', $str);
$md5_v = substr(md5($str.',0123456'), 0, 10);
$hex = unpack('H*', $str);
$pack = $hex[1].$md5_v;

echo $pack.'<br>';exit;

/**
 * 用php实现的解密过程
 */
// $str = '733239407A7467616D652E636F6D2C36393539322CCDF5D5D7B2D332303730362C3233302C333331333936393238322C7332392C323031372D30332D32322031333A32393A3236';
// $str = '7869616f6a756e62696e3132332c31322ccbabcfdf31c7f82c322c3137303230393831302ccaf3b1ea746573742c323031332d31322d32352031383a30383a3534a92688cd4c';
$str = '733232407a7467616d652e636f6d2c36393632322c467265655a54323039b2dfbbaeb7fecef1c6f7732c3233302c3634313736363237362c7332322c323031362d30352d32372030383a34303a3137b527d5929d';
$check_1 = pack('H*', substr($str, 0, -10)).',0123456';
$data = iconv('GBK', 'UTF-8', $check_1);var_dump($data);
$me_value = substr(md5($check_1), 0, 10);
$md5_v = substr($str, -10, 10);
var_dump($me_value, $md5_v);