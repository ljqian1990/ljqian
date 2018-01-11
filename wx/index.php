<?php 
set_time_limit(0);
$fp = fopen('data1', 'r');
$data = '';
while(!feof($fp)){
	$line = fgets($fp);
	preg_match_all('/(.*?)email(.*?)/', $line, $matches);
	if (!empty($matches[0])) {
		$data .= $line;	
	}
}
file_put_contents('data2', $data);
echo 'OK';
