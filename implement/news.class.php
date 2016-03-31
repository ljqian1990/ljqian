<?php
require_once('base.class.php');
class news extends base{
	public function test(){
		parent::test();
	}
}
$news = new news();
// $news->hello();
$news->test();