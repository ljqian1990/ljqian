<?php

/**
 * Class Md5RSA
 * @link http://www.cnblogs.com/kennyhr/p/3746100.html
 */
class Md5RSA
{
    /**
     * 私钥文件路径
     * @var string $rsaPrivateKeyFilePath
     */
    public $rsaPrivateKeyFilePath;

    /**
     * 公钥文件路径
     * @var string $rsaPublicKeyFilePath
     */
    public $rsaPublicKeyFilePath;

    private $_lastError = '';

    public function __construct()
    {

    }

    /**
     * 利用约定数据和私钥生成数字签名
     *
     * @param string $data 待签数据
     *
     * @return bool|string 返回签名
     */
    public function sign($data = '')
    {
        if (empty($data)) {
            return False;
        }
        $private_key = file_get_contents($this->rsaPrivateKeyFilePath);
        if (empty($private_key)) {
            $this->_setError(400, 'Private Key error!');
            return False;
        }
//         $pkeyid = openssl_get_privatekey($private_key);
        $pkeyid = openssl_get_privatekey($private_key, 'ljqian1990');
        
        if (empty($pkeyid)) {
            $this->_setError(400, 'private key resource identifier False!');
            return False;
        }

        $verify = openssl_sign($data, $signature, $pkeyid, OPENSSL_ALGO_MD5);
        openssl_free_key($pkeyid);
        $signature = base64_encode($signature);
        return $signature;
    }

    /**
     * 利用公钥和数字签名以及约定数据验证合法性
     *
     * @param string $data 待验证数据
     * @param string $signature 数字签名
     *
     * @return bool|int -1:error验证错误 1:correct验证成功 0:incorrect验证失败
     */
    public function isValid($data = '', $signature = '')
    {
        if (empty($data) || empty($signature)) {
            return False;
        }

        $public_key = file_get_contents($this->rsaPublicKeyFilePath);
        if (empty($public_key)) {
            $this->_setError(400, 'Public Key error!');
            return False;
        }

        $pkeyid = openssl_get_publickey($public_key);
        if (empty($pkeyid)) {
            $this->_setError(400, 'public key resource identifier False!');
            return False;
        }

        $ret = openssl_verify($data, $signature, $pkeyid, OPENSSL_ALGO_MD5);
        switch ($ret) {
            case -1:
                echo "error";
                break;
            default:
                echo $ret == 1 ? "correct" : "incorrect";//0:incorrect
                break;
        }
        return $ret;
    }

    /**
     * @param string $code
     * @param string $errMsg
     */
    private function _setError($code, $errMsg)
    {
        echo $errMsg . PHP_EOL;
        $this->_lastError = array(
            'code' => $code,
            'errMsg' => $errMsg,
        );
    }

    /**
     * @return string
     */
    public function getLastError()
    {
        return $this->_lastError;
    }
}