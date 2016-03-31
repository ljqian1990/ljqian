<?php


class Autoloader{
	public function __construct(){
		spl_autoload_register(array($this, 'loader'));
	}
	
	private function loader($classname){
		echo 'try to load '.$classname.'<br/>';
		include $classname.'.php';
	}
}