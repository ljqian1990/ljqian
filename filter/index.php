<?php
$start = microtime(true);
$filter = file_get_contents('filter.txt');
$filter_arr = explode("\r\n", $filter);

$subject = '这是一个测试的句子，这是一个测试的句子，这是一个测试的句子，这是一个测试的句子，这是一个测试的句子，这是一个测试的句子，这是一个测试的句子，这是一个测试的句子';

$isok = 1;
foreach ($filter_arr as $pattern) {
    if (preg_match('/'.$pattern.'/', $subject, $match)) {
        $isok = 0;
        var_dump($match);
        break;
    }
}
$end = microtime(true);


echo $isok.'<br>';

echo $end-$start;