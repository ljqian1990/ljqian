<?php
$data = file_get_contents('./data');
var_dump($data);
echo '<br>';
// $data = iconv('GBK', 'UTF-8', $data);
$data = mb_convert_encoding($data, 'UTF-8', 'GBK');
var_dump($data);
