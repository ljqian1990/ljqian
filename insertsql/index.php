<?php
$data  = file_get_contents('./data');
$arr = explode("\r\n",$data);
$sql = 'INSERT INTO `ydmatch_teamdata` (`id`, `teamid`, `teamname`, `nickname`, `playerid`, `datetime`) VALUES'."\r\n";

$nowtime = date('Y-m-d H:i:s');
foreach ($arr as $value) {
    if (!empty($value)) {
        $tmp = explode(",", $value);
        $teamname  = trim($tmp[2]);
        $nickname = trim($tmp[3]);
        $sql .= "({$tmp[0]}, {$tmp[1]}, '{$teamname}', '{$nickname}', '{$nickname}', '{$nowtime}'),\r\n";
    }
}

file_put_contents('./sql.sql', $sql);