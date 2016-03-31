<?php
class Human {
	private 
	$name,
	$age;
	
	public function __construct($name, $age){
		$this->setName($name);
		$this->setAge($age);
	}
	
	public function setName($name){
		$this->name = $name;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setAge($age){
		$this->age = $age;
	}
	
	public function getAge(){
		return $this->age;
	}
	
}