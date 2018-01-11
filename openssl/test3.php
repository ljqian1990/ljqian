<?php
$key = 'afcfc7966b28b6dfc667395912650f57';
// $iv = '2b41a307496edd66';
$iv = '2b41a307496edd660d6759c3ef2ab097';

/*
$plaintext = "message to be encrypted";
$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
//$key = openssl_random_pseudo_bytes($ivlen);
//$iv = openssl_random_pseudo_bytes($ivlen);
$iv = '1234567890123456';
$ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
$ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );

echo $ciphertext;exit;*/

// $ciphertext = 'MTIzNDU2Nzg5MDEyMzQ1NtDNPqF/oFWvkRNdGO6uL9mhw7y5X/AzbqjfxS0OBfBYy694/JZ1H1HEktDYXhLm4l9xP6jEG27PKPtiUBl8bj4=';




// $c = base64_decode($ciphertext);
// $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
// $iv = substr($c, 0, $ivlen);

//echo $iv;

//$iv = '02f1b0c589ea1a6a845a5d3536cfc1e7';
//$iv = pack('H*', '02f1b0c589ea1a6a845a5d3536cfc1e7');

// $iv = '1234567890123456';

$ciphertext_raw = '8apivwLYQGQJSvTQPjBcRh/jOcafH8qIAGwcaIxyDQX8dJaAQMY2xmEe6SnwyDJ8rNe2nPc5L84r9mcp2yT0CqiR8kLycuWILiSQ4v7XQv19XTOdZBK+O1Z79fpRk4VweA+X6Po/fJyuDSUY5c4BU8dRnCvD+2qAyy2xtB2Crbc=';
$ciphertext_raw = base64_decode($ciphertext_raw);

// $hmac = substr($c, $ivlen, $sha2len=32);
// $ciphertext_raw = substr($c, $ivlen+$sha2len);
$original_plaintext = openssl_decrypt($ciphertext_raw, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
// $original_plaintext = openssl_decrypt($ciphertext_raw, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
// $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
// if (hash_equals($hmac, $calcmac))//PHP 5.6+ timing attack safe comparison
// {
    var_dump($original_plaintext);exit; 
    // }

    
//     function stripPkcs7Padding($string)
//     {
//         $slast = ord(substr($string, - 1));
    
//         $slastc = chr($slast);
    
//         $pcheck = substr($string, - $slast);
    
//         if (preg_match("/$slastc{" . $slast . "}/", $string)) {
    
//             $string = substr($string, 0, strlen($string) - $slast);
    
//             return $string;
//         } else {
    
//             return false;
//         }
//     }