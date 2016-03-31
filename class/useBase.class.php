<?php
class useBase {
	private $base;
	
	public function __construct($base){
		$this->base = $base;
	}
	
	public function say(){
		$this->base->hello();
	}
}