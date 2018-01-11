<?php
function encrypt($encrypt, $key) {

    $encrypt = pkcs5_pad($encrypt);
    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_DES, MCRYPT_MODE_ECB), MCRYPT_RAND);
    $passcrypt = mcrypt_encrypt(MCRYPT_DES, $key, $encrypt, MCRYPT_MODE_ECB, $iv);
    // $encode = base64_encode($encode);
    // return $encode;
    return bin2hex($passcrypt);
}

function decrypt($decrypt, $key) {
    ini_set('display_errors', 0);

    //$decoded = base64_decode($decrypt);
    $decoded = pack("H*", $decrypt);
    
    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_DES, MCRYPT_MODE_ECB), MCRYPT_RAND);
    $decrypted = mcrypt_decrypt(MCRYPT_DES, $key, $decoded, MCRYPT_MODE_ECB, $iv);
    return pkcs5_unpad($decrypted);
}
function pkcs5_unpad($text) {
    $pad = ord($text{strlen($text)-1});

    if ($pad > strlen($text)) return $text;
    if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return $text;
    return substr($text, 0, -1 * $pad);
}

function pkcs5_pad($text) {
    $len = strlen($text);
    $mod = $len % 8;
    $pad = 8 - $mod;
    return $text.str_repeat(chr($pad),$pad);
}

$key = 'register';

$uid = '51255cb6d8f8268d49d049281fd435a0';
$account = '75c6f8887207b1a40dc650560454ce8a6d1aaf17e29e74dd';
$showaccount = '10e6a5235c2fba6c84040f0558f21855';
$mphone = '10e6a5235c2fba6c84040f0558f21855';

// $info['uid'] = pkcs5_unpad(decrypt($uid, $key));
$info['uid_v2'] = decrypt($uid, $key);
$info['account'] = decrypt($account, $key);
$info['showaccount'] = decrypt($showaccount, $key);
$info['mphone'] = decrypt($mphone, $key);

var_dump($info);exit;

function decrypt_v2($endata, $key){
    $td = mcrypt_module_open(MCRYPT_DES, "", MCRYPT_MODE_ECB, "");//使用MCRYPT_DES算法,ecb模式
    $size = mcrypt_enc_get_iv_size($td);       //设置初始向量的大小
    $iv = mcrypt_create_iv($size, MCRYPT_RAND); //创建初始向量
    $key_size = mcrypt_enc_get_key_size($td);       //返回所支持的最大的密钥长度（以字节计算）
    
    $salt = '';
    
//     $subkey = substr(md5(md5($key).$salt), 0,$key_size);//对key复杂处理，并设置长度
    
//     $subkey = substr($key, 0,$key_size);//对key复杂处理，并设置长度
 
    $subkey = $key;
    
    mcrypt_generic_init($td, $subkey, $iv);
    $data = rtrim(mdecrypt_generic($td, $endata)).'\n';
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
    return $data;
}