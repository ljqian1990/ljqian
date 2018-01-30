<?php

$systemid = 123456;
$mode = 'c';
$permissions = 0755;
$size = 1024;

$shmid = shmop_open($systemid, $mode, $permissions, $size);

echo $shmid . "\r\n";

shmop_write($shmid, 'hello world 1', 0);

echo shmop_read($shmid, 0, shmop_size($shmid));

echo "\r\n";

var_dump(shmop_delete($shmid));

echo "\r\n";

echo shmop_read($shmid, 0, shmop_size($shmid));

shmop_close($shmid);