<?php
require_once 'operateType.class.php';
require_once 'add.class.php';
require_once 'minus.class.php';
require_once 'mul.class.php';
require_once 'divide.class.php';
require_once 'operate.class.php';


// $type = $_GET['type'];
$num1 = (int)$_GET['num1'];
$num2 = (int)$_GET['num2'];

$operate = new operate($num1, $num2, 4);
echo $operate->getOperate();

exit;

$sum = 0;
switch ($type){
	case 'add':
		$sum = $num1 + $num2;
		break;
	case 'minus':
		$sum = $num1 - $num2;
		break;
	case 'mul':
		$sum = $num1 * $num2;
		break;
	case 'divide':
		try {
			$sum = $num1 / $num2;
		}catch (Exception $ex){
			echo $ex->getMessage();
		}		
		break;
}

echo $sum;

