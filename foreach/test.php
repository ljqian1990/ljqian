<?php
$array = [
    'key1' => 1,
    'key2' => 2,
    'key3' => 3,
    'key4' => 4,
    'key5' => 5
];

// echo current($array) . '<br>';

foreach ($array as $key=>&$value) {
    $value = $value+1;
}

var_dump($array);

foreach ($array as $k=>$value) {
    $value = $value+2;
}

var_dump($array);
//  echo current($array) . '<br>';