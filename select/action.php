<?php
require_once 'init.php';

$act = $_GET['act'];
switch ($act){
	case 'sitehot':
		$account = 'ljqian';
		$siteid = $_GET['siteid'];		
		if (!empty($siteid)){
			try {
				$siteControl = siteControl::getInstance();
				$siteControl->setAccount($account);
				$siteControl->saveHot($siteid);
				echo json_encode(array('ret'=>1));exit;
			}catch (Exception $ex){
				echo json_encode(array('ret'=>0));exit;
			}			
		}
		
		break;
}