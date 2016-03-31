<?php
// $str1 = file_get_contents('compare_str1');
// $str2 = file_get_contents('compare_str2');

$str1 = 'ABCDEFGHIJKLMNOPQRS';
$str2 = 'DCGSRQPOZ';

$timestart = microtime(true);
var_dump(test1($str1, $str2));
$timeend = microtime(true);
echo 'test1 time:'.($timeend-$timestart);

echo '<br/>';

$timestart = microtime(true);
var_dump(test2($str1, $str2));
$timeend = microtime(true);
echo 'test2 time:'.($timeend-$timestart);

echo '<br/>';

$timestart = microtime(true);
var_dump(test3($str1, $str2));
$timeend = microtime(true);
echo 'test3 time:'.($timeend-$timestart);


function test1($str1, $str2){
	$str_one_arr = explode(',', chunk_split($str1, 1, ','));
	$str_two_len = strlen($str2);
	for ($i=0; $i<$str_two_len; $i++){
		if(!in_array($str2[$i], $str_one_arr)){
			$bool = true;
			break;
		}
	}
	return isset($bool)? false:true;
}

function test2($str1, $str2){
	$str2_len = strlen($str2);
	for($i=0; $i<$str2_len; $i++){
		if(!strpbrk($str1, $str2[$i])){
			$bool = true;
			break;
		}
	}
	return isset($bool)?false:true;
}


function test3($str1, $str2){
	$timestart = microtime(true);
	$str1_arr = array_flip(array_unique(str_split($str1)));
	$str2_arr = array_unique(str_split($str2));
	$timeend = microtime(true);
	echo 'test4 time:'.($timeend-$timestart);
	foreach ($str2_arr as $tmp){
		if(!array_key_exists($tmp, $str1_arr)){
			$bool = true;
			break;
		}
	}
	return isset($bool)?false:true;
}