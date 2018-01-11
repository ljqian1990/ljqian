<?php
function addPkcs7Padding($string, $blocksize = 32)
{
    $len = strlen($string); // 取得字符串长度
    
    $pad = $blocksize - ($len % $blocksize); // 取得补码的长度
    
    $string .= str_repeat(chr($pad), $pad); // 用ASCII码为补码长度的字符， 补足最后一段
    
    return $string;
}

function aes256cbcEncrypt($str, $iv, $key)
{
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, addPkcs7Padding($str), MCRYPT_MODE_CBC, $iv));
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
function aes256cbcDecrypt($encryptedText, $iv, $key)
{
    $encryptedText = base64_decode($encryptedText);
    
    return stripPkcs7Padding(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $encryptedText, MCRYPT_MODE_CBC, $iv));
}

$e = aes256cbcEncrypt("8a8f1b22698211e8a25b00155d01e207", '2b41a307496edd660d6759c3ef2ab097', 'afcfc7966b28b6dfc667395912650f57');

//echo $e, "<br>";

$e = '8apivwLYQGQJSvTQPjBcRh/jOcafH8qIAGwcaIxyDQX8dJaAQMY2xmEe6SnwyDJ8rNe2nPc5L84r9mcp2yT0CqiR8kLycuWILiSQ4v7XQv19XTOdZBK+O1Z79fpRk4VweA+X6Po/fJyuDSUY5c4BU8dRnCvD+2qAyy2xtB2Crbc=';

echo aes256cbcDecrypt($e, '2b41a307496edd660d6759c3ef2ab097', 'afcfc7966b28b6dfc667395912650f57');