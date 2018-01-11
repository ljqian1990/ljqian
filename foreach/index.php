<?php
$str = '';
foreach (range(301,400) as $num) {
    $str .= $num."\r\n";
}
file_put_contents('./card2.txt', $str);

$str = '';
foreach (range(401,500) as $num) {
    $str .= $num."\r\n";
}
file_put_contents('./card3.txt', $str);

$str = '';
foreach (range(501,600) as $num) {
    $str .= $num."\r\n";
}
file_put_contents('./card4.txt', $str);

$str = '';
foreach (range(601,700) as $num) {
    $str .= $num."\r\n";
}
file_put_contents('./card5.txt', $str);

