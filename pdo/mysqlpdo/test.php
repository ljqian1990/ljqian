<?php
use Zend\Db\Sql\Ddl\Column\Varbinary;
include 'mysql.class.php';
class Place
{
	private $db;
	
	public function __construct()
	{
		$this->db = new mysqlDB();
	}
	
	public function getAll($id){
		$result = $this->db->selectOne('place', '*', array('where'=>array('id'=>$id)));
		$result = array($result);
		$result = $this->getList($id, $result);
		return $result;
	}
	
	public function getList($pid=0, &$result = array())
	{
		$res = $this->db->select('place', '*', array('where'=>array('parent_id'=>$pid)));
		if (!empty($res)) {
			foreach ($res as $value) {
				$result[] = $value;
				$this->getList($value['id'], $result);
			}
		}
		return $result;
	}
}

$place = new Place();
$starttime = microtime(true);
foreach (range(0, 1000) as $value){
	$result = $place->getAll(2);
}
$endtime = microtime(true);
echo $endtime-$starttime;
//4.1420001983643
//http://ljqian.local.com/pdo/mysqlpdo/test.php