<?php
$ret = file_get_contents("./data");
$arr = json_decode($ret, true);

$sql = '';
foreach ($arr as $value) {
    $sql .= "insert into ggao (`name`,`url`,`tag`) VALUES ('{$value['key']}', '{$value['val']}', '{$value['key']}');";
}
echo $sql;