<?php
/**
 * ������ģʽ
 *
 * �ṩһ������˳�����һ�ۺ϶����еĸ���Ԫ��,���ֲ���¶������ڲ���ʾ
 */
// interface Interator {
// 	public function next();
// 	public function first();
// 	public function current();
// 	public function isDone();
// }
class SomeInterator implements Iterator {
	private $_arr = array ();
	public function __construct($arr) {
		$this->_arr = $arr;
	}
	public function first() {
		return $this->_arr [0];
	}
	public function current() {
		return current ( $this->_arr );
	}
	public function next() {
		return next ( $this->_arr );
	}
	public function isDone() {
	}
}

$objSomeInterator = new SomeInterator ( array (
		1,
		2,
		3,
		4,
		6,
		7 
) );
echo $objSomeInterator->first (), "<br/>";
echo $objSomeInterator->next (), "<br/>";
echo $objSomeInterator->current (), "<br/>";
echo $objSomeInterator->current (), "<br/>";
echo $objSomeInterator->next (), "<br/>";
echo $objSomeInterator->current (), "<br/>";