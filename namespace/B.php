<?php
require_once 'A.php';
use A as C;

class news {
	public function __construct(){
		echo 'B news<br/>';
	}
}

class app {
	public function __construct(){
		echo 'B app<br/>';
	}
}

class comment {
	private $comment;

	public function __construct(){
		$this->comment = new C\comment;
	}
}

$c = new comment();

$n = new news();

$a = new app();