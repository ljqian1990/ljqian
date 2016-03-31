<?php
include_once 'base.class.php';
class news implements base {
	
	public function __construct(){
		
	}
	
	public function exist(){
		
	}
	
	public function getDetail(){
		
	}
	
	public function _echo(){
		return 'hello';
	}
}



$news = new news();
echo $news->_echo();