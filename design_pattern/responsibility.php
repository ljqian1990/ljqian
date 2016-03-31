<?php
/**
 * ְ����ģʽ
 *
 * Ϊ�������ķ����ߺͽ�����֮������,��ʹ�ö�������û��ᴦ���������,����Щ��������һ����,���������������ݸ�����,ֱ����һ����������
 *
 */
abstract class Handler {
	protected $_handler = null;
	public function setSuccessor($handler) {
		$this->_handler = $handler;
	}
	abstract function handleRequest($request);
}
class ConcreteHandlerZero extends Handler {
	public function handleRequest($request) {
		if ($request == 0) {
			echo "0<br/>";
		} else {
			$this->_handler->handleRequest ( $request );
		}
	}
}
class ConcreteHandlerOdd extends Handler {
	public function handleRequest($request) {
		if ($request % 2) {
			echo $request . " is odd<br/>";
		} else {
			$this->_handler->handleRequest ( $request );
		}
	}
}
class ConcreteHandlerEven extends Handler {
	public function handleRequest($request) {
		if (! ($request % 2)) {
			echo $request . " is even<br/>";
		} else {
			$this->_handler->handleRequest ( $request );
		}
	}
}

// ʵ��һ��
$objZeroHander = new ConcreteHandlerZero ();
$objEvenHander = new ConcreteHandlerEven ();
$objOddHander = new ConcreteHandlerOdd ();
$objZeroHander->setSuccessor ( $objEvenHander );
$objEvenHander->setSuccessor ( $objOddHander );

foreach ( array (
		2,
		3,
		4,
		5,
		0 
) as $row ) {
	$objZeroHander->handleRequest ( $row );
}