<?php
$fp = fsockopen('192.168.39.4', 9601, $error, $errstr, 3);
stream_set_blocking($fp, 0);
fwrite($fp, '44');
// while (!feof($fp)) {
    $str = fgets($fp, 128);
    file_put_contents('./data', $str);
// }
echo 'OK';
sleep(6);