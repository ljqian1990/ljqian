<?php
header ( "Content-type:text/html;charset=utf-8" );
require_once 'src/phpQuery.php';
phpQuery::newDocumentFileHTML('http://www.qq.com/');
echo pq('img:eq(0)')->attr('src');