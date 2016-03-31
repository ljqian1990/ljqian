<?php
require_once ("../lib/nusoap.php");
/*
通过 WSDL 调用 WebService
参数 1 WSDL 文件的地址 ( 问号后的 wsdl 不能为大写 )
参数 2  指定是否使用 WSDL
$client = new soapclient('http://localhost/nusoapService.php?wsdl',true);
*/
$client = new soapclient ('http://localhost/ljqian/nusoap/example/server.php?wsdl',true);
$client->soap_defencoding = 'UTF-8';
$client->decode_utf8 = false;
$client->xml_encoding = 'UTF-8';
// 参数转为数组形式传递
$paras = array ('name' => 'Bruce Lee' );
// 目标方法没有参数时，可省略后面的参数
$client->call ( 'GetTestStr', $paras );
$document = $client->document;
echo $document; 
?>