<?php
/**
 * ����ģʽ
 *
 * ��֤һ�������һ��ʵ��,���ṩһ����������ȫ�ַ��ʵ�
 *
 */
class Singleton {
	private static $_instance = null;
	private function __construct() {
	}
	static public function getInstance() {
		if (is_null ( self::$_instance )) {
			self::$_instance = new Singleton ();
		}
		return self::$_instance;
	}
	public function display() {
		echo "it is a singlton class function";
	}
}

// $obj = new Singleton(); // �������ܳɹ�
$obj = Singleton::getInstance ();
var_dump ( $obj );
$obj->display ();

$obj1 = Singleton::getInstance ();
var_dump ( ($obj === $obj1) );