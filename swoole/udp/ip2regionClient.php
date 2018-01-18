<?php
$ip = trim($_GET['ip']);
$fp = fsockopen("udp://192.168.39.4", 20000, $errno, $errstr);
if (!$fp) {
    echo "ERROR: $errno - $errstr<br />\n";
} else {
    fwrite($fp, $ip);
    echo fread($fp, 8192);
    fclose($fp);
}