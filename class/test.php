<?php
spl_autoload_register ( 'loader' );

$a = new Abase();
$ub = new useBase($a);
$ub->say();

function loader($class) {
	$file = $class . '.class.php';
	$classpath = '../class/' . $file;
	
	if (file_exists ( $classpath )) {
		require_once ( $classpath );
	} else {
		return false;
	}
}