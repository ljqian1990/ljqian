<?php
class siteModel2 {
	private $db;
	
	private $table_site = 'site';
	private $table_sitehot = 'sitehot';
	
	const SEVEN_DAY = 604800;
	
	public function __construct() {
		$this->db = new mysqlDB ();
	}	
	
	public function insert($data) {
		$cookies = $this->getCookie();
		if (!empty($cookies)){
			foreach ($cookies as $key=>$cookie){
				if ($cookie['siteid'] == $data['siteid']){
					unset($cookies[$key]);
				}
			}
			$cookies = array_values($cookies);
		}
		
		$cookies[] = $data;			
		$this->setCookie($cookies);
	}
	
	public function selectAll() {
		$list = $this->db->select($this->table_site, '*');
		return $list;
	}
	
	public function selectByCookie() {
		$cookies = $this->getCookie();
		if (!empty($cookies)){
			krsort($cookies);
		}
		return $cookies;
	}
	
	private function getCookie(){
		$cookies_str = isset($_COOKIE[$this->table_sitehot]) ? $_COOKIE[$this->table_sitehot] : '';
		if (empty($cookies_str)){
			return array();
		}else{
			$cookie_arr = unserialize($cookies_str);
			$cookie_arr = $this->delete($cookie_arr);
			return $cookie_arr;
		}
	}
	
	private function delete($cookies) {
		if (!empty($cookies)){
			foreach ($cookies as $key=>$cookie){
				if ($cookie['date'] <= date('Y-m-d', time()-self::SEVEN_DAY)){
					unset($cookies[$key]);
				}
			}
			$cookies = array_values($cookies);
		}
		return $cookies;
	}
	
	private function setCookie($data=array()){
		setcookie($this->table_sitehot, serialize($data), time()+self::SEVEN_DAY);
	}
}