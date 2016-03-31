<?php
class operate {
	const OPERATE_ADD = 1;
	const OPERATE_MINUS = 2;
	const OPERATE_MUL = 3;
	const OPERATE_DIVIDE = 4;
	
	private $operateType;
	
	private $num1;
	private $num2;
	
	public function __construct($num1, $num2, $type){
		$this->num1 = $num1;
		$this->num2 = $num2;
		
		$this->operateType = $this->create($type);
	}
	
	private function create($type){
		switch ($type){
			case self::OPERATE_ADD:
				return new add();
				break;
			case self::OPERATE_MINUS:
				return new minus();
				break;
			case self::OPERATE_MUL:
				return new mul();
				break;
			case self::OPERATE_DIVIDE:
				return new divide();
				break;
		}
	}
	
	public function getOperate(){
		return $this->operateType->operate($this->num1, $this->num2);
	}
	
	public function changeType($type){
		$this->operateType = $type;
	}
}