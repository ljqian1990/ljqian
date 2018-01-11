<?php
$ret = file_get_contents('./data.txt');
$arr = explode("\n", $ret);
$sql = '';
foreach ($arr as $val) {
    list($g, $m, $w, $e, $f) = explode(",", $val);
    $sql .= "update ydmatch_teamember set `mvpnum`=$m,totalwinnernum=$w,eatnum=$e,fansnum=$f where gameid='$g'\n\r";
}

file_put_contents('./updatesql.sql', $sql);