<?php
include_once 'base.php';
class news extends base {
	public function __construct(){
		echo $this->test3();
	}
	
	protected function test1(){
	
	}
	
}

$news = new news();
