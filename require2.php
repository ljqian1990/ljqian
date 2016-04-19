<?php 
class requireClass
{
	private $siteid;
	
	public function __construct()
	{
		$siteid = $_REQUEST['siteid'];
		$this->setSiteid($siteid);
	}
	
	public function needChangeSiteid()
	{
		$siteid = $_REQUEST['siteid'];
		$this->setSiteid($siteid);
	}
	
	public function pprint()
	{
		return $this->getSiteid()."<br>";
	}
	
	private function setSiteid($siteid)
	{
		$this->siteid = $siteid;
	}
	
	private function getSiteid()
	{
		return $this->siteid;
	}
}


$_REQUEST['siteid'] = 1;
$require = new requireClass();
echo $require->pprint();

$siteid_tmp = $_REQUEST['siteid'];
$_REQUEST['siteid'] = 2;
$require->needChangeSiteid();
echo $require->pprint();
$_REQUEST['siteid'] = $siteid_tmp;

$require = new requireClass();
echo $require->pprint();

