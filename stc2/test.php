<?php 
namespace ljqian\stc2;
class Employee{
	private $_type;
	
	private $_monthlySalary = 1;
	private $_commission = 2;
	private $_bonus = 3;
	
	public function __construct($type){
		$this->setType($type);
	}
	
	public function getMonthlySalary(){
		return $this->_monthlySalary;
	}
	public function getCommission(){
		return $this->_commission;
	}
	public function getBonus(){
		return $this->_bonus;
	}
	
	private function setType($type){
		$this->_type = EmployeeType::newType($type);
	}
	
	public function payAmount(){
		return $this->_type->payAmount($this);
	}
}

abstract class EmployeeType{
	const ENGINEER = 0;
	const SALESMAN = 1;
	const MANAGER = 2;
	
	abstract public function payAmount($employee);
	
	abstract public function getTypeCode();
	
	public static function newType($type){
		switch ($type){
			case self::ENGINEER:
				return new engineer();
			case self::SALESMAN:
				return new salesman();
			case self::MANAGER:
				return new manager();
			default:
				throw new Exception('Incorrect Employee');
		}
	}
}

class engineer extends EmployeeType{
	public function getTypeCode(){
		return self::ENGINEER;
	}
	
	public function payAmount($employee){
		return $employee->getMonthlySalary();
	}
}
class salesman extends EmployeeType{
	public function getTypeCode(){
		return self::SALESMAN;
	}
	
	public function payAmount($employee){
		return $employee->getMonthlySalary() + $employee->getCommission();
	}
}
class manager extends EmployeeType{
	public function getTypeCode(){
		return self::MANAGER;
	}
	
	public function payAmount($employee){
		return $employee->getMonthlySalary() + $employee->getBonus();
	}
}

$e0 = new Employee(0);
echo $e0->payAmount();
echo "<br>";

$e1 = new Employee(1);
echo $e1->payAmount();
echo "<br>";

$e2 = new Employee(2);
echo $e2->payAmount();