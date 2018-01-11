<?php
$array = array(
    'name' => 'ljqian',
    'age' => 28
);

$arr = array();
foreach (range(0, 100000) as $val) {
    array_push($arr, $array);
}

$start = microtime(true);
$startm = memory_get_usage();
$str = json_encode($arr);
$end = microtime(true);
$endm = memory_get_usage();
echo 'json_encode:' . ($end - $start) . '<br>';
echo 'json_encode:' . ($endm - $startm) . '<br>';

$start = microtime(true);
$startm = memory_get_usage();
json_decode($str, true);
$end = microtime(true);
$endm = memory_get_usage();
echo 'json_decode:' . ($end - $start) . '<br>';
echo 'json_decode:' . ($endm - $startm) . '<br>';

$start = microtime(true);
$startm = memory_get_usage();
$str2 = serialize($arr);
$end = microtime(true);
$endm = memory_get_usage();
echo 'serialize:' . ($end - $start) . '<br>';
echo 'serialize:' . ($endm - $startm) . '<br>';

$start = microtime(true);
$startm = memory_get_usage();
unserialize($str2);
$end = microtime(true);
$endm = memory_get_usage();
echo 'unserialize:' . ($end - $start) . '<br>';
echo 'unserialize:' . ($endm - $startm) . '<br>';
