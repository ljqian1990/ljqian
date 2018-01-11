<?php

$datalist = array(
    array('No'=>1, 'Fuin'=>'10000', 'iNum'=>1000, 'fdate'=>'2015-03-27', 'remark1'=>'500', 'remark2'=>'300', 'remark3'=>'200'),
    array('No'=>2, 'Fuin'=>'10001', 'iNum'=>2000, 'fdate'=>'2015-03-28', 'remark1'=>'1000', 'remark2'=>'600', 'remark3'=>'400'),
    array('No'=>3, 'Fuin'=>'10002', 'iNum'=>3000, 'fdate'=>'2015-03-29', 'remark1'=>'1500', 'remark2'=>'900', 'remark3'=>'600'),
    array('No'=>4, 'Fuin'=>'10003', 'iNum'=>4000, 'fdate'=>'2015-03-30', 'remark1'=>'2000', 'remark2'=>'1200', 'remark3'=>'800'),
    array('No'=>5, 'Fuin'=>'10004', 'iNum'=>5000, 'fdate'=>'2015-03-31', 'remark1'=>'2500', 'remark2'=>'1500', 'remark3'=>'1000')
);

//5实现多维数组排序
function _handle_ranking_list($ranklist, $options=array()){
    if(!empty($ranklist) && !empty($options)){
        foreach ($ranklist as $key => $value) {
            foreach($options as $option){
                $tmp[$option['key']][$key] = $value[$option['key']];
            }
        }
        $str = 'array_multisort(';
        foreach ($options as $option){
            $str .= '$tmp["'.$option['key'].'"], '.$option['order'].', ';
        }
        $str .= '$ranklist);';

        eval($str);
    }
    return $ranklist;
} 
  
// $datalist = _handle_ranking_list($datalist, array(array('key'=>'iNum', 'order'=>'SORT_DESC')));  
// print_r($datalist);exit;  
  
// 二维数组排序， $arr是数据，$keys是排序的健值，$order是排序规则，1是降序，0是升序  
function array_sort_v1($arr, $keys, $order = 0) {  
    if (! is_array ( $arr )) {  
        return false;  
    }  
    $keysvalue = array ();  
    foreach ( $arr as $key => $val ) {  
        $keysvalue [$key] = $val [$keys];  
    }  
    if ($order == 0) {  
        asort ( $keysvalue );  
    } else {  
        arsort ( $keysvalue );  
    }  
    reset ( $keysvalue );  
    foreach ( $keysvalue as $key => $vals ) {  
        $keysort [$key] = $key;  
    }  
    $new_array = array ();  
    foreach ( $keysort as $key => $val ) {  
        $new_array [$key] = $arr [$val];  
    }  
    return $new_array;  
}   

// $datalist = array_sort_v1($datalist, 'No', 0);
// print_r($datalist);

//二位数组排序节约代码版  
function array_sort_v2($arr, $key_order, $order=0) {  
    if (!is_array($arr)) {  
        return false;
    }  
      
    $arrValue = array();  
    foreach ($arr as $key=>$value) {  
        $arrValue[$key] = $value[$key_order];  
    }  
      
    if ($order == 0) {  
        asort($arrValue);  
    } else {  
        arsort($arrValue);  
    }     
      
    $arrnew = array();  
    foreach ($arrValue as $key=>$value) {  
        $arrnew[$value] = $arr[$key];  
    }  
    return $arrnew;  
}  

$datalist = array_sort_v2($datalist, 'No', 1);
print_r($datalist);