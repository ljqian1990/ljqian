<?php
include 'mysql.class.php';
$db = new MysqlDB();
$sql = 'select * from place where FIND_IN_SET(id, getChildList(:id))';
$starttime = microtime(true);
foreach (range(0, 1000) as $value){
	$result = $db->run($sql, array('id'=>2));
}
$endtime = microtime(true);
echo $endtime-$starttime;
//1.8261818885803