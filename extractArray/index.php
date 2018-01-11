<?php
function extractArray($arr=[], $paramnew=[], $paramold=[])
{
    if (empty($arr) || empty($paramnew) || empty($paramold)) {
        return [];
    }

    if (count($paramnew) != count($paramold)) {
        return [];
    }

    $list = [];
    foreach ($arr as $key=>$val) {
        array_map(function($pn, $po) use ($val, $key, &$list){
            $list[$key][$pn] = $val[$po];
        }, $paramnew, $paramold);
    }
    return $list;
}

$arr[] = ['name'=>'ljqian', 'age'=>28, 'sex'=>1, 'address'=>'11111'];
$arr[] = ['name'=>'ljqian1', 'age'=>281, 'sex'=>11, 'address'=>'111111'];
$arr[] = ['name'=>'ljqian2', 'age'=>282, 'sex'=>12, 'address'=>'111112'];

$list = extractArray($arr, ['namen', 'agen', 'addn'], ['name', 'age', 'address']);
print_r($list);

function extractOneDimensionalArray($arr = [], $paramnew = [], $paramold = [])
{
    if (empty($arr) || empty($paramnew) || empty($paramold)) {
        return [];
    }


    $list = [];
    array_map(function ($pn, $po) use($arr, &$list) {
        $list[$pn] = $arr[$po];
    }, $paramnew, $paramold);
    return $list;
}

$arr = ['name'=>'ljqian', 'age'=>28, 'sex'=>1, 'address'=>'11111'];
$list = extractOneDimensionalArray($arr, ['namen', 'sexn'], ['name','sex']);
print_r($list);