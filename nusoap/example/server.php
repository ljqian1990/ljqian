<?php
require_once ("../lib/nusoap.php");
$server = new soap_server ();
// ��������
$server->soap_defencoding = 'UTF-8';
$server->decode_utf8 = false;
$server->xml_encoding = 'UTF-8';
$server->configureWSDL ('test'); // �� wsdl ֧��
/*
ע����Ҫ���ͻ��˷��ʵĳ���
���Ͷ�Ӧֵ�� bool->"xsd:boolean"    string->"xsd:string"
int->"xsd:int"     float->"xsd:float"
*/
$server->register ( 'GetTestStr', // ������
array ("name" => "xsd:string" ), // ������Ĭ��Ϊ "xsd:string"
array ("return" => "xsd:string" ) ); // ����ֵ��Ĭ��Ϊ "xsd:string"
//isset  �������Ƿ�����
$HTTP_RAW_POST_DATA = isset ( $HTTP_RAW_POST_DATA ) ? $HTTP_RAW_POST_DATA : '';
//service  ����ͻ������������
$server->service ( $HTTP_RAW_POST_DATA );
/**
 * �����õķ���
 * @param $name
 */
function GetTestStr($name) {
	return "Hello,  { $name } !";
}
?>
