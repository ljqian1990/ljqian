<?php

    # --- 加密 ---

    # 密钥应该是随机的二进制数据，
    # 开始使用 scrypt, bcrypt 或 PBKDF2 将一个字符串转换成一个密钥
    # 密钥是 16 进制字符串格式

// $str = 'qwertyuioplkjhgfdsazxcvbnm0123456789';
// $arr = str_split($str);
// $newstr = '';
// for ($i=0;$i<10;$i++){
//     $newstr .= $arr[array_rand($arr)];
// }
// echo $newstr."<br>";
// echo strlen($newstr);exit;

    $key = pack('H*', "e8809101b1ba80cdd0819eff29542dd01e72db0dae093ad238e223fb2d67e5b5");
    
    # 显示 AES-128, 192, 256 对应的密钥长度：
    #16，24，32 字节。
//     $key_size =  strlen($key);
//     echo "Key size: " . $key_size . "\n";
    
    $plaintext = "This string was AES-256 / CBC / ZeroBytePadding encrypted.";

    # 为 CBC 模式创建随机的初始向量
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    

    # 创建和 AES 兼容的密文（Rijndael 分组大小 = 128）
    # 仅适用于编码后的输入不是以 00h 结尾的
    # （因为默认是使用 0 来补齐数据）
    $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,
                                 $plaintext, MCRYPT_MODE_CBC, $iv);
// echo $ciphertext;exit;
    # 将初始向量附加在密文之后，以供解密时使用
    $ciphertext = $iv . $ciphertext;
    
    # 对密文进行 base64 编码
    $ciphertext_base64 = base64_encode($ciphertext);

    echo  $ciphertext_base64 . "\n";

    # === 警告 ===

    # 密文并未进行完整性和可信度保护，
    # 所以可能遭受 Padding Oracle 攻击。
    
    # --- 解密 ---
    
    $ciphertext_dec = base64_decode($ciphertext_base64);
    
    # 初始向量大小，可以通过 mcrypt_get_iv_size() 来获得
    $iv_dec = substr($ciphertext_dec, 0, $iv_size);
    
    # 获取除初始向量外的密文
    $ciphertext_dec = substr($ciphertext_dec, $iv_size);

    # 可能需要从明文末尾移除 0
    $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,
                                    $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
    
    echo  trim($plaintext_dec, "\0") . "\n";