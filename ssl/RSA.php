<?php
/**
 * 生成公共与私有证书操作
 * openssl req -x509 -out public_key.der -outform der -new -newkey rsa:1024 -keyout private_key.pem
 * 生成测试用公共证书操作
 * openssl rsa -in private_key.pem -pubout -out public_key.pem
 * 在本地进行加密调试时，使用public_key.pem证书
 * @author ljqian<ljqian@163.com>
 */
class RSA{
	private $_filename;
	private $_key_content;

	public function __construct($filename){
		if(empty($filename)){
			$this->_show_msg('ERROR');
		}
		$this->_filename = $filename;
		$this->_get_key_content();
		if(empty($this->_key_content)){
			$this->_show_msg('ERROR');
		}
	}
	/**
	 * 加密操作
	 * @param string $sourcestr	源数据
	 * @return string $base64_encode_str 加密后的输出数据
	 */
	public function publickey_encode($sourcestr){
		$pubkeyid = openssl_get_publickey($this->_key_content);
		if(openssl_public_encrypt($sourcestr, $crypttext, $pubkeyid)){
			return base64_encode("".$crypttext);
		}
	}
	/**
	 * 解密操作
	 * @param string $crypttext 源数据
	 * @return string $sourcestr 解密后的输出数据
	 */
	public function privatekey_decode($crypttext){
		$prikeyid = openssl_pkey_get_private($this->_key_content, 'ljqian1990');
		$crypttext = base64_decode($crypttext);
		if(openssl_private_decrypt($crypttext, $sourcestr, $prikeyid, OPENSSL_PKCS1_PADDING)){
			//do success
			return $sourcestr;
		}
	}
	/**
	 * 获取证书内容
	 */
	private function _get_key_content(){
		$this->_key_content = file_get_contents($this->_filename);
	}

	private function _show_msg($msg){
		echo $msg;exit;
	}
}

$rsa_pub = new RSA('public_key.pem');//导入公共证书
$pub_content = $rsa_pub->publickey_encode('hello world');//加密操作
var_dump($pub_content);

$rsa_pri = new RSA('private_key.pem');//导入私有证书
echo $rsa_pri->privatekey_decode($pub_content);//解密操作
