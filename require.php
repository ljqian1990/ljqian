<?php

use Zend\Db\Sql\Ddl\Column\Varbinary;
class ljqian
{
	public static function test()
	{
		$require = requireClass::getInstance();
		echo $require->toString();
		echo requireClass::getSiteId()."<br>";

		requireClass::backupFromMemento();
		echo $require->toString();
		echo requireClass::getSiteId()."<br>";
	}
}

class requireClass
{
	private static $_instance = null;
	
	private $siteId;	
	private $model;	
	private $action;
	
	private $memento;	
	
	public static function getInstance($siteId = null, $model = null, $action = null)
	{
		if (is_null(self::$_instance)) {			
			self::$_instance = new self($siteId, $model, $action);			
		}
		return self::$_instance;
	}
	
	public static function getSiteId()
	{
		return self::getInstance()->_getSiteId();
	}
	
	public static function getModel()
	{
		return self::getInstance()->_getModel();
	}
	
	public static function getAction()
	{
		return self::getInstance()->_getAction();
	}
	
	public static function setSiteId($siteId)
	{
		self::getInstance()->_setSiteId($siteId);
	}
	
	public static function setModel($model)
	{
		self::getInstance()->_setModel($model);
	}
	
	public static function setAction($action)
	{
		self::getInstance()->_setAction($action);
	}
	
	public static function backupFromMemento()
	{
		self::getInstance()->_backupFromMemento();
	}
	
	private function __construct($siteId, $model, $action)
	{
		$this->_setSiteId($siteId);
		$this->_setModel($model);
		$this->_setAction($action);		
		
		$this->setMemento(clone $this);
	}
	
	private function _setSiteId($siteId)
	{
		$this->siteId = $siteId;
	}
	
	private function _getSiteId()
	{
		return $this->siteId;
	}
	
	private function _setModel($model)
	{
		$this->model = $model;
	}
	
	private function _getModel()
	{
		return $this->model;
	}
	
	private function _setAction($action)
	{
		$this->action = $action;
	}
	
	private function _getAction()
	{
		return $this->action;
	}
	
	private function _backupFromMemento()
	{
		$this->_setSiteId($this->getMemento()->_getSiteId());
		$this->_setModel($this->getMemento()->_getModel());
		$this->_setAction($this->getMemento()->_getAction());
	}
	
	public function toString()
	{
		return $this->_getSiteId() . ' ' . $this->_getModel() . ' ' . $this->_getAction() . "<br>";
	}
	
	private function setMemento($memento)
	{
		$this->memento = $memento;
	}
	
	private function getMemento()
	{
		return $this->memento;
	}

}

$require = requireClass::getInstance(1, 'news', 'model');
echo $require->toString();
echo requireClass::getSiteId()."<br>";

requireClass::setSiteId(2);
echo $require->toString();
echo requireClass::getSiteId()."<br>";
// $require->backupFromMemento();
// echo $require->toString();




ljqian::test();

















