<?php
$mysqlhost = 'localhost';
$usrname = 'test';
$password =  'test';
$dbname = 'siteapi';

$con = mysqli_connect($mysqlhost, $usrname , $password);//������ݿ��û���������
if (!$con)
{
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_query($con, 'set names utf8');// �����ַ���
mysqli_select_db($con, $dbname);//ѡ��������ݿ�
$sql ="SHOW TABLES;";
echo  $sql;
if(!$ret = mysqli_query($con, $sql)) {
    echo "mysqli_query " . mysqli_error($con);
}
mysqli_close($con);//�ر�����
var_dump($ret);exit;
