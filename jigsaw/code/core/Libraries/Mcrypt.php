<?php
namespace Projectname\Libraries;

class Mcrypt
{
    private static $_self;
    
    private $secretkey = 'e8809101b1ba80cdd0819eff29542dd01e72db0dae093ad238e223fb2d67e5b5';
    
    private $key;
    private $iv;
    private $iv_size;

    public function __construct($key, $iv, $iv_size)
    {
        if (empty($key) || empty($iv) || empty($iv_size)) {
            Exception::throwUserException(Config::error('MCRYPT_PARAMS_EMPTY'));
        }
        $this->key = $key;
        $this->iv = $iv;
        $this->iv_size = $iv_size;
        $this->init();
    }
    
    private function init()
    {
        $this->key = pack('H*', $this->secretkey);
        $this->iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $this->iv = mcrypt_create_iv($this->iv_size, MCRYPT_RAND);
    }

    public function encode($plaintext)
    {
        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key, $plaintext, MCRYPT_MODE_CBC, $this->iv);
        $ciphertext = $this->iv . $ciphertext;
        $ciphertext_md5 = substr(md5($ciphertext.$this->secretkey), 12, 10);
        $ciphertext_base64 = base64_encode($ciphertext);
        return $ciphertext_base64.$ciphertext_md5;
    }

    public function decode($ciphertext)
    {
        $strlen = strlen($ciphertext);
        $ciphertext_base64 = substr($ciphertext, 0, $strlen-10);
        $ciphertext_md5 = substr($ciphertext, $strlen-10, 10);        
        $ciphertext_dec = base64_decode($ciphertext_base64);
        $check_ciphertext_md5 = substr(md5($ciphertext_dec.$this->secretkey), 12, 10);
        if ($ciphertext_md5 != $check_ciphertext_md5) {
            Exception::throwSysException(Config::error('SYS_MCRYPT_DECODE_MD5_CHECK_ERROR'));
        }
        
        $iv_dec = substr($ciphertext_dec, 0, $this->iv_size);
        $ciphertext_dec = substr($ciphertext_dec, $this->iv_size);
        $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
        return trim($plaintext_dec, "\0");
    }
}