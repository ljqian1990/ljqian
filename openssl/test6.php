<?php
class Gamecrypt
{
    
    private $key = 'afcfc7966b28b6dfc667395912650f57';
    
    private $iv = '2b41a307496edd660d6759c3ef2ab097';
    
    //private $key = 'afcfc7966b28b6df';
    
    //private $iv = '2b41a307496edd66';
    
    public function __construct($key='', $iv='')
    {
        $this->setKey($key);
        $this->setIv($iv);
    }

    public function stripPkcs7Padding($string)
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

    public function aes256cbcDecrypt($encryptedText)
    {
        $encryptedText = base64_decode($encryptedText);        
        return $this->stripPkcs7Padding(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->getKey(), $encryptedText, MCRYPT_MODE_CBC, $this->getIv()));
    }
    
    public function aes128cbcDecrypt($encryptedText)
    {
        $encryptedText = base64_decode($encryptedText);
        return $this->stripPkcs7Padding(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->getKey(), $encryptedText, MCRYPT_MODE_CBC, $this->getIv()));
    }
    
    public function opensslDecrypt($encryptedText)
    {
        $encryptedText = base64_decode($encryptedText);
        return openssl_decrypt($encryptedText, 'AES-128-CBC', $this->getKey(), OPENSSL_RAW_DATA, $this->getIv());
    }
    
    public function opensslEncrypt($data)
    {
        $data = json_encode($data);
        $encryptedText = openssl_encrypt($data, 'AES-128-CBC', $this->getKey(), OPENSSL_RAW_DATA, $this->getIv());
        return base64_encode($encryptedText);
    }
    
    private function setKey($key)
    {
        if (!empty($key)) {
            $this->key = $key;
        }
    }
    
    private function getKey()
    {
        return $this->key;
    }
    
    private function setIv($iv)
    {
        if (!empty($iv)) {
            $this->iv = $iv;
        }
    }
    
    private function getIv()
    {
        return $this->iv;
    }
}


//$str = 'iny7Hn+TWsisBPHZc3xLIyuLgrvpNajPmxBftO5A3iMJ1fBWqHGoI2JcfgEcPE+lhCxYbNgzBgKdPpBtaUrt+mahAFsxL1g78kgryTaB2PASKPtmCyvDzvTDpsadIPNC4r5f60K3TSHN+kqEy6+GrSM0wrbUNNve/U+lny+xpp3D2ML/vFbc2h3aQUlAnwXq';

//$str = 'iny7Hn+TWsisBPHZc3xLI7VZadpgTf1T1ME9+rYiiFSs8ucgSxnfkZXc7GIEUfCJB9jrEt7BScAeOMl/djL+uxvwjpF33syzeb7ioQvIjfWsCpSmtiIz3TAV2G65F/QJdJau46uRvKwYOJBIhWir/3eEIEGehSW0ATz7oec9UkKdAaD6fUqW3xWnJJllAhZ8BplrjnyKS2XJqOdeMi1CE+FNB/YnVIfRCwutJbJgITrAYA0zdduGhO5LXMrWuNpaFEGRI+G35/UCXvVdSmZ+LQ==';

$str = 'aG3xVAiqcqSJP2ioCqLxj7tVrStDoUpePgZP+nupo9+8EPGKpJRW+Gtcs5f/WGADMq01dm/O7GMG4VCU/McrRhr6ndyXMuXmJm12rvT3h25kgtQfR0HbNwH/XocDGqnM71RsWfEnGj2nMUIejF1dJfsv8P/YX9kaVudcLdOroaQ=';

$str = str_replace(' ', '+', $str);
//$str = base64_decode($str);


$Gamecrypt = new Gamecrypt();
$ret = $Gamecrypt->aes256cbcDecrypt($str);
//$ret = $Gamecrypt->aes128cbcDecrypt($str);
//$ret = $Gamecrypt->opensslDecrypt($str);
var_dump($ret);exit;