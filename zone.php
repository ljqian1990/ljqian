<?php

use Zend\Db\Sql\Ddl\Column\Varbinary;
$fp = fopen('zonedata', 'r');
$list = array();
while(!feof($fp)){
	$line = fgets($fp);
	$data = array();
	list($data['game'], $data['zone'], $data['zoneType'], $data['ip'], $data['port'], $data['name'], $data['type'], $data['cap'], $data['x'], $data['y'], $data['desc'], $data['IsUse'], $data['desc_order'], $data['destGame'], $data['destZone'], $data['flag_test'], $data['PYDesc'], $data['operation']) = explode('	', $line);
	$list[$data['zone']] = array('zone'=>$data['zone'], 'name'=>$data['name'], 'destZone'=>$data['destZone'], 'isUse'=>$data['IsUse']);
}

foreach ($list as $key=>&$zone) {
	$lastzone = getLastZone($list, $zone);
	$zone['name'] = $lastzone['name'];
}

var_dump($list);

function getLastZone($list, $zone)
{
	if ($zone['IsUse'] == 1) {
		return $zone;
	} else {
		if ($zone['destZone'] != 0 && isset($list[$zone['destZone']])) {
			return getLastZone($list, $list[$zone['destZone']]);
		} else {
			return $zone;
		}
		
	}
}