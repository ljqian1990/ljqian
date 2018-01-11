<?php
/**
 * @version  1.0
 * @author   Dongjun
 * @date     2017-03-22
 */

class GiantMobile
{

    private $_apiUrl = NULL;

    private $_gameType = NULL;

    private $_errorsMap = Array(
        '-1008' => '注册失败',
        '-1004' => '解密失败',
        '0' => '成功',
    );

	private $key = 'aUe*^dxq';
	private $iv = 'aUe*^dxq'; //偏移量、加解密向量

    public function convertError($errorCode)
    {
        return isset($this->_errorsMap[$errorCode]) ? $this->_errorsMap[$errorCode] : '';
    }

    // 接口地址
    public function setApiUrl($apiUrl)
    {
        $this->_apiUrl = $apiUrl;
    }

    // 游戏类型
    public function setGameType($gameType)
    {
        $this->_gameType = $gameType;
    }

    /**
     * 巨人通行证注册
     *
     * @param		account		手机号码
     * @param		udid		信息内容(256个字节以内)
     * @param		pwd			巨人通行证账号的密码
     * @param		name		用户真实姓名(可选)
     * @param		idcard		用户的身份证号(可选)
	 *
     * @return array
     */
    public function regist($account, $udid, $pwd, $name = '', $idcard = '')
    {

        $pwd = $this->encryptV2($pwd, $this->key, $this->iv);
        $pwd = urlencode(urlencode($pwd));

        $uri = $this->_apiUrl."/ManageServlet?action=reg&account={$account}&udid={$udid}&pwd={$pwd}";
		if(!empty($name)) $uri .= "&name={$name}";
		if(!empty($idcard)) $uri .= "&idcard={$idcard}";

		$result = $this->readUrl($uri);
        return $result;
    }

    protected function readUrl($url)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        if (is_array($data)) {
            $this->pushError($data['code'], $data['strInfo']);
            // 将字符串转为utf8否则在json_encode时会报错
            $url = mb_convert_encoding($url, 'UTF-8', 'ascii,GB2312,gbk,UTF-8');
            // 记录日志
            Log::info('input:' . $url . '|code:' . $data['code']);
        } else {
            $this->pushError(- 99, '无法解析服务器返回值>>' . $returnString);
        }

        return $data;
    }

    // 解析服务器返回的字符串
    protected function parseReturnString($returnString)
    {
        $data = Array();
        
        $parseString = trim(iconv("gbk", "utf-8", urldecode($returnString)), '&');
        
        $data_temp = explode('&', $parseString);
        if (is_array($data_temp))
            foreach ($data_temp as $val) {
                $temp = explode('=', $val);
                if (count($temp) == 2)
                    $data[$temp[0]] = $temp[1];
            }
        
        return $data;
    }
    
    // 记录错误信息
    protected function pushError($errorCode, $errorMsg = '')
    {
        $this->_stateCode = $errorCode;
        if ($errorMsg === '') {
            // 如果错误列表中有关于该错误编码的含义
            if ($errorMsg = $this->convertError($errorCode)) {
                $this->_errorMsg[] = $errorMsg;
            }
        } else {
            $this->_errorMsg[] = $errorMsg;
        }
    }
    
    // 取得错误信息，如查$isString为空，则返回数组，否则返回字符串
    public function getErrorMsg($isString = '')
    {
        if ($isString != '') {
            return implode($isString, $this->_errorMsg);
        } else {
            return $this->_errorMsg;
        }
    }
    
    // 错误编码
    public function getStateCode()
    {
        return $this->_stateCode;
    }

    protected function setStateCode($code)
    {
        $this->_stateCode = $code;
    }

	//加密
	public function encryptV2($str)
	{
	    $key = $this->key;
	    $iv = $this->iv;
		$size = mcrypt_get_block_size ( MCRYPT_DES, MCRYPT_MODE_CBC );
		$str = $this->pkcs5Pad ( $str, $size );
		$data=mcrypt_cbc(MCRYPT_DES, $key, $str, MCRYPT_ENCRYPT, $iv);
		return base64_encode($data);
	}
	
	public function encryptOpenssl($str)
	{
	    $data = $str;
	    $encryptedText = openssl_encrypt($data, 'AES128', $this->key, OPENSSL_RAW_DATA, $this->iv);
	    return base64_encode($encryptedText);
	}
	
	public function ecryptmcrypt($str)
	{
	    
	}
 
    //解密
    public function decryptV2($str)
    {
        $key = $this->key;
        $iv = $this->iv;
        $str = base64_decode ($str);
        $str = mcrypt_cbc(MCRYPT_DES, $key, $str, MCRYPT_DECRYPT, $iv );
        $str = $this->pkcs5Unpad( $str );
        return $str;
    }
 
    public function encryptV1($input)
    {
        $key = $this->key;
        $iv = $this->iv;
        $size = 8; //填充块的大小,单位为bite    初始向量iv的位数要和进行pading的分组块大小相等!!!  
        $input = $this->pkcs5Pad($input, $size);  //对明文进行字符填充  
        $td = mcrypt_module_open(MCRYPT_DES, '', 'cbc', '');    //MCRYPT_DES代表用DES算法加解密;'cbc'代表使用cbc模式进行加解密.  
        mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);    //对$input进行加密  
        mcrypt_generic_deinit($td);  
        mcrypt_module_close($td);  
        $data = base64_encode($data);   //对加密后的密文进行base64编码  
        return $data;  
    }  
 
    /* 
     * 在采用DES加密算法,cbc模式,pkcs5Padding字符填充方式,对密文进行解密函数 
     */
    public function decryptV1($crypt) {  
        $key = $this->key;
        $iv = $this->iv;
        $crypt = base64_decode($crypt);   //对加密后的密文进行解base64编码  
        $key = $ky;  
        $iv = $iv;  //$iv为加解密向量  
        $td = mcrypt_module_open(MCRYPT_DES, '', 'cbc', '');    //MCRYPT_DES代表用DES算法加解密;'cbc'代表使用cbc模式进行加解密.  
        mcrypt_generic_init($td, $key, $iv);  
        $decrypted_data = mdecrypt_generic($td, $crypt);    //对$input进行解密  
        mcrypt_generic_deinit($td);  
        mcrypt_module_close($td);  
        $decrypted_data = $this->pkcs5Unpad($decrypted_data); //对解密后的明文进行去掉字符填充  
        $decrypted_data = rtrim($decrypted_data);   //去空格  
        return $decrypted_data;
    }

     /* 
     * 对明文进行给定块大小的字符填充 
     */  
	private function pkcs5Pad($text, $blocksize)
	{
		$pad = $blocksize - (strlen ( $text ) % $blocksize);
		return $text . str_repeat ( chr ( $pad ), $pad );
	}

     /* 
     * 对解密后的已字符填充的明文进行去掉填充字符 
     */ 
	private function pkcs5Unpad($text)
	{
		$pad = ord ( $text {strlen ( $text ) - 1} );
		if ($pad > strlen ( $text ))
			return false;
		if (strspn ( $text, chr ( $pad ), strlen ( $text ) - $pad ) != $pad)
			return false;
		return substr ( $text, 0, - 1 * $pad );
	}

}

function pkcs5Pad($text, $blocksize)
{
    $pad = $blocksize - (strlen ( $text ) % $blocksize);
    return $text . str_repeat ( chr ( $pad ), $pad );
}

function addPkcs7Padding($string, $blocksize = 32)
{
    $len = strlen($string); // 取得字符串长度

    $pad = $blocksize - ($len % $blocksize); // 取得补码的长度

    $string .= str_repeat(chr($pad), $pad); // 用ASCII码为补码长度的字符， 补足最后一段

    return $string;
}

function aes128cbcEncrypt($str)
{
    $iv='aUe*^dxq';
    $key='aUe*^dxq';
    $size = mcrypt_get_block_size ( MCRYPT_DES, MCRYPT_MODE_CBC );
    return base64_encode(mcrypt_encrypt(MCRYPT_DES, $key, pkcs5Pad($str, $size), MCRYPT_MODE_CBC, $iv));
}

echo 'encrypt:'.aes128cbcEncrypt('ljqian');



$gm = new GiantMobile();




/*
$str = $gm->encryptOpenssl('ljqian');
echo 'encrypt:'.$str;
echo '<br>';

*/

/*
$str = $gm->encryptV2('ljqian');
echo 'encrypt:'.$str;
echo '<br>';
$ret = $gm->decryptV2($str);
var_dump($ret);
*/


