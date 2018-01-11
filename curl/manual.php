<?php
$func = $_REQUEST['func'];
// $func = 'array_​fill';
// $func = 'array_replace';
$func = str_replace('_', '-', $func);
$url = 'http://php.net/manual/zh/function.'.$func.'.php';//var_dump($url);exit;
// $url = urldecode(str_replace('%E2%80%8B', '', urlencode($url)));
// $url = str_replace('%E2%80%8B', '', $url);
// var_dump(mb_convert_encoding($url, 'gbk', 'utf-8'));exit;
// var_dump();exit;
// $url = dirname(__FILE__).'/data';
$data = file_get_contents($url);
// file_put_contents(dirname(__FILE__).'/data', $data);exit;

preg_match('/<h1 class="refname">(.*?)<\/h1>/', $data, $matchfuncname);
echo $funcname = $matchfuncname[1];
echo "<br>";

preg_match('/<p class=\"refpurpose\">(.*?)<\/p>/s', $data, $matchtitle);
echo $title = strip_tags($matchtitle[1]);
echo "<br>";

preg_match('/<div class="methodsynopsis dc-description">(.*?)<\/div>/s', $data, $matchdesc);
echo $desc = strip_tags($matchdesc[1]);
echo "<br>";

preg_match('/<div class="refsect1 parameters" id="(.*?)">(.*?)<\/div>/s', $data, $matchparam);
echo $param = strip_tags($matchparam[2]);
echo "<br>";

preg_match('/<div class="refsect1 returnvalues" id="(.*?)">(.*?)<\/div>/s', $data, $matchreturn);
echo $return = strip_tags($matchreturn[2]);
echo "<br>";

