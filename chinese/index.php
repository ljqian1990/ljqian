<?php
function ischinese($s) {    
    if (preg_match("/^[a-zA-Z0-9-_]*$/", $s)) {
        return 1;
    } else {
        return 0;
    }    
}

$str = '中文嗯';
echo ischinese($str);

$str = '字节字符编码范';
echo ischinese($str);

$str = 'SEO';
echo ischinese($str);

$str = '\x20-\x7f';
echo ischinese($str);

$str = 'preg_match';
echo ischinese($str);

$str = '中是否含有中';
echo ischinese($str);

$str = 'eg_match_all( ... PHP';
echo ischinese($str);