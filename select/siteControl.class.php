<?php
class siteControl {
	private static $_self;
	
	private $account;
	
	private function __construct() {
		$this->model = new siteModel();
	}
	
	public function setAccount($account){
		$this->account = $account;
	}
	
	public static function getInstance() {
		if (! self::$_self) {
			self::$_self = new self ();
		}
		return self::$_self;
	}
	
	public function saveHot($siteid){		
		$data = array('account'=>$this->getAccount(), 'siteid'=>$siteid, 'date'=>date('Y-m-d'));
		$this->model->insert($data);
	}
	
	public function showSiteList(){		
		$sitelist = $this->getSitelist();
		$sitehotlist = $this->getSitelist($this->getAccount());
		$list = $this->hotSort($sitelist, $sitehotlist);		
		return $list;
	}
	
	private function getAccount(){
		if (empty($this->account)){
			throw new Exception('帐号不能为空');
		}
		return $this->account;
	}
	
	private function getSitelist($account = ''){
		$list = array();
		if (empty($account)){
			$list = $this->model->selectAll();
		}else{
			$select = 'siteid,count(*) as sum';
			$where = array('account'=>$account, 'date'=>array('>='=>date('Y-m-d', time()-24*3600*7)));
			$group = 'siteid';
			$order = 'sum desc,id desc';
			$list = $this->model->selectByCookie();
		}
		
		return $list;
	}
	
	private function hotSort($sitelist, $sitehotlist){
		$sitelist_key_siteid = array();
		if (!empty($sitelist)){			
			foreach ($sitelist as $value){
				$sitelist_key_siteid[$value['siteid']] = array('siteid'=>$value['siteid'], 'sitename'=>$value['sitename']);
			}
		}
		
		$result = array();
		if (!empty($sitehotlist)){
			foreach ($sitehotlist as $value){
				$result[$value['siteid']] = array('siteid'=>$value['siteid'], 'sitename'=>$sitelist_key_siteid[$value['siteid']]['sitename']);
			}			
		}
		
		if (!empty($sitelist_key_siteid)){
			foreach ($sitelist_key_siteid as $value){
				if (! isset($result[$value['siteid']]) ){
					$result[$value['siteid']] = $value;
				}
			}
		}
		
		$result = array_values($result);
		return $result;
	}
}