<?php
/**
 * ԭ��ģʽ
 *
 * ��ԭ��ʵ��ָ���������������.����ͨ���������ԭ���������µĶ���
 *
*/
abstract class Prototype{
	private $_id=null;
    
	public function __construct($id){
		$this->_id =$id;
    }

	public function getID(){
		return $this->_id;
    }

	public function __clone(){
		$this->_id += 1;
    }

	public function getClone(){
		return clone $this;
    }
}

class ConcretePrototype extends Prototype
{
}

//
$objPrototype = new ConcretePrototype(0);
$objPrototype1 = clone $objPrototype;
echo $objPrototype1->getID()."<br/>";
$objPrototype2 = $objPrototype;
echo $objPrototype2->getID()."<br/>";

$objPrototype3 = $objPrototype->getClone();
echo $objPrototype3->getID()."<br/>";