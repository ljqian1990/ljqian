<?php
/**
 * ����ģʽ(Strategy.php)
 *
 * ����һϵ���㷨,������һ������װ����,����ʹ���ǿ��໥�滻,ʹ�õ��㷨�ı仯�ɶ�����ʹ�����Ŀͻ�
 *
 */

// ---������һϵ���㷨�ķ��----
interface CacheTable {
	public function get($key);
	public function set($key, $value);
	public function del($key);
}

// ��ʹ�û���
class NoCache implements CacheTable {
	public function __construct() {
		echo "Use NoCache<br/>";
	}
	public function get($key) {
		return false;
	}
	public function set($key, $value) {
		return true;
	}
	public function del($key) {
		return false;
	}
}

// �ļ�����
class FileCache implements CacheTable {
	public function __construct() {
		echo "Use FileCache<br/>";
		// �ļ����湹�캯��
	}
	public function get($key) {
		// �ļ������get����ʵ��
	}
	public function set($key, $value) {
		// �ļ������set����ʵ��
	}
	public function del($key) {
		// �ļ������del����ʵ��
	}
}

// TTServer
class TTCache implements CacheTable {
	public function __construct() {
		echo "Use TTCache<br/>";
		// TTServer���湹�캯��
	}
	public function get($key) {
		// TTServer�����get����ʵ��
	}
	public function set($key, $value) {
		// TTServer�����set����ʵ��
	}
	public function del($key) {
		// TTServer�����del����ʵ��
	}
}

// -- ������ʹ�ò��û���Ĳ��� ------
class Model {
	private $_cache;
	public function __construct() {
		$this->_cache = new NoCache ();
	}
	public function setCache($cache) {
		$this->_cache = $cache;
	}
}
class UserModel extends Model {
}
class PorductModel extends Model {
	public function __construct() {
		$this->_cache = new TTCache ();
	}
}

// -- ʵ��һ�� ---
$mdlUser = new UserModel ();
$mdlProduct = new PorductModel ();
$mdlProduct->setCache ( new FileCache () ); // �ı仺�����
?>