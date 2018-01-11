<?php

echo strtolower('中文11SSaa');exit;


include_once('./pinyin.php');
$namelist = file_get_contents('./data.txt');
$namearr = explode("\r\n", $namelist);
$pinyinarr = [];
foreach ($namearr as $name) {
    if (!empty($name)) {
        $pinyinarr[] = Pinyin($name, 'utf-8');
    }
}

file_put_contents('./data', implode("\r\n", $pinyinarr));
var_dump($pinyinarr);exit;