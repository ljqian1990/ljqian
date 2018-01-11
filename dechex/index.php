<?php
$ymd = '20161208';
echo $ymd;
echo "<br>";
echo dechex($ymd);
echo "<br>";
$arr = unpack('c*', $ymd);
$hex = $arr[1];
echo $hex;
echo "<br>";
echo base_convert(ord($ymd), 10, 16);
echo "<br>";
echo chr(base_convert($hex, 16, 10));
echo "<br>";
echo pack("c*", $arr[1]);
echo "<br>";
echo base_convert($arr[1], 16, 2);

