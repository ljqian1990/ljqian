<?php
/**
 * ������ģʽ
 *
 * ��һ����Ľӿ�ת���ɿͻ�ϣ��������һ���ӿ�,ʹ��ԭ�������ݵĶ�������һ��������Щ�������һ����
 */

// �����ԭ�е�����
class OldCache {
	public function __construct() {
		echo "OldCache construct<br/>";
	}
	public function store($key, $value) {
		echo "OldCache store<br/>";
	}
	public function remove($key) {
		echo "OldCache remove<br/>";
	}
	public function fetch($key) {
		echo "OldCache fetch<br/>";
	}
}
interface Cacheable {
	public function set($key, $value);
	public function get($key);
	public function del($key);
}
class OldCacheAdapter implements Cacheable {
	private $_cache = null;
	public function __construct() {
		$this->_cache = new OldCache ();
	}
	public function set($key, $value) {
		return $this->_cache->store ( $key, $value );
	}
	public function get($key) {
		return $this->_cache->fetch ( $key );
	}
	public function del($key) {
		return $this->_cache->remove ( $key );
	}
}

$objCache = new OldCacheAdapter ();
$objCache->set ( "test", 1 );
$objCache->get ( "test" );
$objCache->del ( "test", 1 );