<?php
function addPkcs7Padding($string, $blocksize = 16)
{
    $len = strlen($string); // 取得字符串长度

    $pad = $blocksize - ($len % $blocksize); // 取得补码的长度

    $string .= str_repeat(chr($pad), $pad); // 用ASCII码为补码长度的字符， 补足最后一段

    return $string;
}

function aes256cbcEncrypt($str, $iv, $key)
{
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, addPkcs7Padding($str), MCRYPT_MODE_CBC, $iv));
}

$key = '1234567890123456';
$iv = '1234567890123456';


$e = aes256cbcEncrypt("hello world", $key, $iv);

echo $e;
// bAx40eFUVf/hIxbaV8/GaQ==


$e = base64_decode($e);

$original_plaintext = openssl_decrypt($e, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);

var_dump($original_plaintext);exit;