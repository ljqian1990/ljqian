<?php

function addPkcs7Padding($string, $blocksize = 32)
{
    $len = strlen($string); // 取得字符串长度
    
    $pad = $blocksize - ($len % $blocksize); // 取得补码的长度
    
    $string .= str_repeat(chr($pad), $pad); // 用ASCII码为补码长度的字符， 补足最后一段
    
    return $string;
}

function aes128cbcEncrypt($str, $iv, $key)
{
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, addPkcs7Padding($str), MCRYPT_MODE_CBC, $iv));
}

function stripPkcs7Padding($string)
{
    $slast = ord(substr($string, - 1));
    
    $slastc = chr($slast);
    
    $pcheck = substr($string, - $slast);
    
    if (preg_match("/$slastc{" . $slast . "}/", $string)) {
        
        $string = substr($string, 0, strlen($string) - $slast);
        
        return $string;
    } else {
        
        return false;
    }
}

function aes128cbcDecrypt($encryptedText, $iv, $key)
{
    $encryptedText = base64_decode($encryptedText);
    
//     return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $encryptedText, MCRYPT_MODE_CBC, $iv);
    return stripPkcs7Padding(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $encryptedText, MCRYPT_MODE_CBC, $iv));
}

// $iv = pack('H*', '845a5d3536cfc1e7');
// $key = pack('H*', "ad1;l3(k-w1c=+zr");

$iv = '845a5d3536cfc1e7';
$key = 'ad1;l3(k-w1c=+zr';

$e =  aes128cbcEncrypt("8a8f1b22698211e8a25b00155d01e207", $iv, $key);
var_dump($e);
$str = aes128cbcDecrypt($e, $iv, $key);
var_dump($str);


// $e = '4ERdNmCW/s4JW2X2sNEwYq1WmCHzHZfHTztGW+OrAgMJvu6JGSTy+LvfPXqLPkzM';

// $str = aes128cbcDecrypt($e, '845a5d3536cfc1e7', 'ad1;l3(k-w1c=+zr');
// var_dump($str);




















