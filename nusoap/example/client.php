<?php
require_once ("../lib/nusoap.php");
/*
ͨ�� WSDL ���� WebService
���� 1 WSDL �ļ��ĵ�ַ (�ʺź��wsdl����Ϊ��д)
���� 2  ָ���Ƿ�ʹ�� WSDL
$client = new soapclient('http://localhost/nusoapService.php?wsdl',true);
*/
$client = new soapclient ('http://localhost/ljqian/nusoap/example/server.php');
$client->soap_defencoding = 'UTF-8';
$client->decode_utf8 = false;
$client->xml_encoding = 'UTF-8';
// ����תΪ������ʽ����
$paras = array ('name' => 'Bruce Lee' );
// Ŀ�귽��û�в���ʱ����ʡ�Ժ���Ĳ���
$result = $client->call ( 'GetTestStr', $paras );
// �����󣬻�ȡ����ֵ
if (! $err = $client->getError ()) {
	echo " ���ؽ���� ", $result;
} else {
	echo " ���ó��� ", $err;
}
?>