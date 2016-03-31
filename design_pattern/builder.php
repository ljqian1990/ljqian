<?php
/**
 * ������ģʽ
 * 
 * ��һ�����Ӷ���Ĺ��������ı�ʾ����,ʹ��ͬ���Ĺ������̿��Դ�����ͬ�ı�ʾ
 */
class Product {
	public $_type = null;
	public $_size = null;
	public $_color = null;
	public function setType($type) {
		echo "set product type<br/>";
		$this->_type = $type;
	}
	public function setSize($size) {
		echo "set product size<br/>";
		$this->_size = $size;
	}
	public function setColor($color) {
		echo "set product color<br/>";
		$this->_color = $color;
	}
}

$config = array (
		"type" => "shirt",
		"size" => "xl",
		"color" => "red" 
);

// û��ʹ��bulider��ǰ�Ĵ���
$oProduct = new Product ();
$oProduct->setType ( $config ['type'] );
$oProduct->setSize ( $config ['size'] );
$oProduct->setColor ( $config ['color'] );

// ����һ��builder��
class ProductBuilder {
	var $_config = null;
	var $_object = null;
	public function ProductBuilder($config) {
		$this->_object = new Product ();
		$this->_config = $config;
	}
	public function build() {
		echo "--- in builder---<br/>";
		$this->_object->setType ( $this->_config ['type'] );
		$this->_object->setSize ( $this->_config ['size'] );
		$this->_object->setColor ( $this->_config ['color'] );
	}
	public function getProduct() {
		return $this->_object;
	}
}

$objBuilder = new ProductBuilder ( $config );
$objBuilder->build ();
$objProduct = $objBuilder->getProduct ();