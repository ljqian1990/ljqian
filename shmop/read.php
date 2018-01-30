<?php

$systemid = 123456;
$mode = 'c';
$permissions = 0755;
$size = 1024;

$shmid = shmop_open($systemid, $mode, $permissions, $size);

echo $shmid . "\r\n";

$ret = shmop_read($shmid, 0, shmop_size($shmid));

echo shmop_delete($shmid);

echo "\r\n";

shmop_close($shmid);

echo $ret;