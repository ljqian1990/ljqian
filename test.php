<?php
ini_set("error_reporting", E_ALL);
// ini_set("display_errors", 1);
class Persion
{
	/**
	 * @deprecated
	 */
	public function showName()
	{
		trigger_error('foobar() is deprecated!');		
// 		call_user_method();
		return 'name';
	}
	
	public function showNameAgain()
	{
		return 'name again';
	}
}

$persion = new Persion();
echo $persion->showName();
// echo $persion->showNameAgain();