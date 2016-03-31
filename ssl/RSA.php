<?php
/**
 * ���ɹ�����˽��֤�����
 * openssl req -x509 -out public_key.der -outform der -new -newkey rsa:1024 -keyout private_key.pem
 * ���ɲ����ù���֤�����
 * openssl rsa -in private_key.pem -pubout -out public_key.pem
 * �ڱ��ؽ��м��ܵ���ʱ��ʹ��public_key.pem֤��
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
	 * ���ܲ���
	 * @param string $sourcestr	Դ����
	 * @return string $base64_encode_str ���ܺ���������
	 */
	public function publickey_encode($sourcestr){
		$pubkeyid = openssl_get_publickey($this->_key_content);
		if(openssl_public_encrypt($sourcestr, $crypttext, $pubkeyid)){
			return base64_encode("".$crypttext);
		}
	}
	/**
	 * ���ܲ���
	 * @param string $crypttext Դ����
	 * @return string $sourcestr ���ܺ���������
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
	 * ��ȡ֤������
	 */
	private function _get_key_content(){
		$this->_key_content = file_get_contents($this->_filename);
	}

	private function _show_msg($msg){
		echo $msg;exit;
	}
}

$rsa_pub = new RSA('public_key.pem');//���빫��֤��
$pub_content = $rsa_pub->publickey_encode('hello world');//���ܲ���
var_dump($pub_content);

$rsa_pri = new RSA('private_key.pem');//����˽��֤��
echo $rsa_pri->privatekey_decode($pub_content);//���ܲ���
