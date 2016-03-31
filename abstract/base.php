<?php
include_once 'old.php';
abstract class base extends old{
	
	abstract protected function test1();
	
	protected function test2(){
		return 'test2';
	}
}