<?php
include 'pgsql.class.php';
$pgDB = new pgDB();
$sql = 'WITH RECURSIVE r AS (SELECT * FROM place WHERE id = :id union ALL SELECT place.* FROM place, r WHERE place.parent_id = r.id) SELECT * FROM r ORDER BY id;';
$starttime = microtime(true);
foreach (range(0, 1000) as $value){
	$result = $pgDB->run($sql, array('id'=>2));
}
$endtime = microtime(true);
echo $endtime-$starttime;
//2.5662569999695
//http://ljqian.local.com/pdo/pgsqlpdo/test.php