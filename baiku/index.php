<?php
$page1 = file_get_contents('json1');
$page2 = file_get_contents('json2');
$page3 = file_get_contents('json3');
$page4 = file_get_contents('json4');

$data = '';

$arr1 = json_decode($page1, true);
foreach ($arr1 as $val) {
    foreach ($val['parags'] as $pa) {
        $data .= $pa['c'] . "\r";
    }
}
$arr2 = json_decode($page2, true);
foreach ($arr2 as $val) {
    foreach ($val['parags'] as $pa) {
        $data .= $pa['c'] . "\r";
    }
}
$arr3 = json_decode($page3, true);
foreach ($arr3 as $val) {
    foreach ($val['parags'] as $pa) {
        $data .= $pa['c'] . "\r";
    }
}
$arr4 = json_decode($page4, true);
foreach ($arr4 as $val) {
    foreach ($val['parags'] as $pa) {
        $data .= $pa['c'] . "\r";
    }
}

file_put_contents('./data', $data);
