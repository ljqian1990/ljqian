<?php
/**
 * ����ģʽ
 *
 * Ϊ���������ṩһ�������Կ����������ķ���
 *
*/
interface Proxy{
	public function request();
	public function display();
}

class RealSubject{
	public function request(){
		echo "RealSubject request<br/>";
    }

	public function display(){
		echo "RealSubject display<br/>";
    }
}

class ProxySubject{
	private $_subject=null;
	public function __construct(){
		$this->_subject = new RealSubject();
    }

	public function request(){
		$this->_subject->request();
    }

	public function display(){
		$this->_subject->display();
    }
}

$objProxy=new ProxySubject();
$objProxy->request();
$objProxy->display();