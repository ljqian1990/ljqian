<?php
namespace A;

class news {
	
	public function __construct(){
		echo 'A news<br/>';
	}
}

class app {
	
	public function __construct(){
		echo 'A app<br/>';
	}
}

class comment {
	private $news;
	private $app;
	
	public function __construct(){
		$this->news = new news();
		$this->app = new app();
	}
}