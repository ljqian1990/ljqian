<?php
$filter = file_get_contents('filter2.txt');

$subject = '这是一个测试的句子，这是一个测试的句子，这是一个测试的句子，这是一个测试的句子，这是一个测试的句子，这是一个测试的句子，这是一个测试的句子，这是一个测试的句子';
$start = microtime(true);
$isok = 1;
if (_preg_match($filter, $subject)) {
    // if (_preg_match($filter, $subject)) {
    $isok = 0;
    break;
}
$end = microtime(true);

echo $isok . '<br>';

echo $end - $start;

function _preg_match($commentfilter, $content)
{
    $commentfilter = str_replace(array(
        ']',
        '/',
        '*',
        '(',
        ')',
        '+',
        '?',
        '{',
        '}',
        '.',
        '^',
        '$'
    ), array(
        '\]',
        '\/',
        '\*',
        '\(',
        '\)',
        '\+',
        '\?',
        '\{',
        '\}',
        '\.',
        '\^',
        '\$'
    ), $commentfilter);
    $len = strlen($commentfilter);
    $i = 1;
    $arr = array();
    $start = 0;
    $offset = 0;
    while ($offset < $len) {
        $offset = 30000 * $i;
        if ($offset > $len) {
            $str_tmp = substr($commentfilter, $start, ($len - $start));
            $arr[] = $str_tmp;
            break;
        } else {
            $pos = strpos($commentfilter, '|', $offset);
            $str_tmp = substr($commentfilter, $start, ($pos - $start));
            $arr[] = $str_tmp;
            
            $start = $pos + 1;
            $i ++;
        }
    }
    
    $status = false;
    foreach ($arr as $k => $v) {
        preg_match('/' . $v . '/', $content, $matches);
        if (! empty($matches) && ! empty($matches[0])) {
            $status = true;
            break;
        }
    }
    return $status;
}