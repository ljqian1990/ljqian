<?php
class xml2pb{
	
	protected $xmlfile;
	
	protected $xmldata;
	
	protected $level = 0;
	
	protected $type;
	
	protected $modifier;
	
	public function __construct($xmlfile, $modifier, $type){
		$this->setXmlFile($xmlfile);
		$this->setModifier($modifier);
		$this->setType($type);
	}
	
	protected function setXmlFile($xmlfile){
		if(!is_file($xmlfile)){
			return false;
		}
		$this->xmlfile = $xmlfile;
	}
	
	protected function setModifier($modifier){
		$this->modifier = $modifier;
	}
	
	protected function setType($type){
		$this->type = $type;
	}
	
	protected function setXmlData(){
		if(!empty($this->xmlfile)){
			$this->xmldata = simplexml_load_file($this->xmlfile);
		}
	}
	
	protected function getParentNode(){
		return $this->xmldata->getName();
	}
	
	protected function xml2Arr($xmldata=''){
		$modifier = $this->modifier;
		$type = $this->type;
		
		if(empty($xmldata)){
			$xmldata = $this->xmldata;
		}
		
		$arr = array();
		foreach ($xmldata as $child){
			if(count($child) > 0){
				$arr[$child->getName()] = $this->xml2Arr($child);
			}else{
				$arr[$child->getName()] = (string)$child.'|'.(string)$child->attributes()->$modifier.'|'.(string)$child->attributes()->$type;
			}
		}
		return $arr;
	}
	
	protected function addTab($num){		
		$str = "";
		if(!empty($num)){
			for ($i=1; $i<=$num; $i++){
				$str .= "\t";
			}	
		}		
		return $str;
	}
	
	protected function arr2Str($arr){
		
		$this->level += 1;
		
		$tab = $this->addtab($this->level);
		
		$str = '';
		
		$i = 0;
		
		foreach ($arr as $k=>$v){
			
			if(is_array($v)){
				$str .= "\n".$tab."message ".$k."\n".$tab."{\n";
				$str .= $this->arr2Str($v);
			}else{				
				$i += 1;
				
				list($value, $req, $type) = explode('|', $v);				
				$str .= $tab."$req $type ".$k." = $i;\t//".$value."\n";
			}
		}
		
		if($this->level > 1){
			$str .= $this->addtab($this->level-1)."}\n\n";
		}
		$this->level -= 1;
		
		return $str;
	}
	
	protected function save($str){
		file_put_contents(dirname($this->xmlfile).'/'.str_ireplace('.xml', '.proto', basename($this->xmlfile)), $str);
	}
	
	public function run(){
		$this->setXmlData();
		$arr[$this->getParentNode()] = $this->xml2Arr();
		$str = $this->arr2Str($arr);
		$this->save($str);
	}
}


$x2p = new xml2pb('d:/workspace/ljqian/xml2pb/test.xml', 'req', 'type');
// $x2p->run();
echo 'OK';

$obj = simplexml_load_file('d:/workspace/ljqian/xml2pb/test.xml');
$arr =  json_decode(json_encode($obj),TRUE);
var_dump($arr);exit;
// $arr = get_object_vars($obj);
// print_r($arr);

















