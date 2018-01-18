<?php
$fp = fsockopen("udp://192.168.39.4", 20000, $errno, $errstr);
if (!$fp) {
    echo "ERROR: $errno - $errstr<br />\n";
} else {
    fwrite($fp, "180.168.126.182");
    echo fread($fp, 8192);
    fclose($fp);
}