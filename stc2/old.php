<?php 
class Employee{
	private $_type;
	
	private $_monthlySalary = 1;
	private $_commission = 2;
	private $_bonus = 3;
	
	const ENGINEER = 0;
	const SALESMAN = 1;
	const MANAGER = 2;
	
	public function __construct($type){
		$this->_type = $type;
	}
	
	public function payAmount(){
		switch($this->_type){
			case self::ENGINEER:
				return $this->_monthlySalary;
			case self::SALESMAN:
				return $this->_monthlySalary + $this->_commission;
			case self::MANAGER:
				return $this->_monthlySalary + $this->_bonus;
			default:
				throw new Exception('Incorrect Employee');
		}
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