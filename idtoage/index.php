<?php
$data = file_get_contents('./data');
$arr = explode("\r\n", $data);
$age = [];
foreach ($arr as $val) {
    if (preg_match('/\d{6}(\d{4})/', $val, $match)) {
        $year = $match[1];
        $age[] = 2016-intval($match[1]);
    }else{
        echo 'error';exit;
    }    
}
$agestr = implode("\r\n", $age);
file_put_contents('./agedate', $agestr);