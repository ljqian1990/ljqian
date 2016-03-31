<?php
set_time_limit(0);
$fp = fopen('hj', 'r');
$fp_write = fopen('data', 'w');

while(!feof($fp)){
	$line = fgets($fp);
	if (!empty($line)){
		list($account, $accid, $roleid, $rolename, $level, $shopping, $lastlogintime, $roomid, $fwq, $class) = explode(',', $line);
		
		fwrite($fp_write, "$account,$accid,$roleid,$rolename ,$level,$shopping,$lastlogintime,$roomid,$fwq,$class\n");
		
		unset($account, $accid, $roleid, $rolename, $level, $shopping, $lastlogintime, $roomid, $fwq, $class);
	}
	
}
echo 'OK';
fclose($fp);
fclose($fp_write);
