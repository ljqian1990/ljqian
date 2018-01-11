<?php
$key = 'abcdefg123456789';
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

$ciphertext = 'MTIzNDU2Nzg5MDEyMzQ1NtDNPqF/oFWvkRNdGO6uL9mhw7y5X/AzbqjfxS0OBfBYy694/JZ1H1HEktDYXhLm4l9xP6jEG27PKPtiUBl8bj4=';




$c = base64_decode($ciphertext);
$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
$iv = substr($c, 0, $ivlen);

//echo $iv;

//$iv = '02f1b0c589ea1a6a845a5d3536cfc1e7';
//$iv = pack('H*', '02f1b0c589ea1a6a845a5d3536cfc1e7');

$iv = '1234567890123456';


$hmac = substr($c, $ivlen, $sha2len=32);
$ciphertext_raw = substr($c, $ivlen+$sha2len);
$original_plaintext = openssl_decrypt($ciphertext_raw, 'AES-256-CBC', $key, $options=OPENSSL_RAW_DATA, $iv);
$calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
if (hash_equals($hmac, $calcmac))//PHP 5.6+ timing attack safe comparison
{
    echo $original_plaintext."\n";
}