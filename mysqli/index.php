<?php
$mysqlhost = 'localhost';
$usrname = 'test';
$password =  'test';
$dbname = 'siteapi';

$con = mysqli_connect($mysqlhost, $usrname , $password);//你的数据库用户名和密码
if (!$con)
{
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_query($con, 'set names utf8');// 设置字符集
mysqli_select_db($con, $dbname);//选择你的数据库
$sql ="SHOW TABLES;";
echo  $sql;
if(!$ret = mysqli_query($con, $sql)) {
    echo "mysqli_query " . mysqli_error($con);
}
mysqli_close($con);//关闭连接
var_dump($ret);exit;