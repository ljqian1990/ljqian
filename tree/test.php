<?php
$list = array (
		0 => array (
				0 => '1',
				'id' => '1',
				1 => '新闻',
				'name' => '新闻',
				2 => '新闻',
				'simp' => '新闻',
				3 => 'news',
				'flag' => 'news',
				4 => '0',
				'root' => '0',
				5 => '1',
				'sort' => '1',
				6 => '1',
				'parents' => '1' 
		),
		1 => array (
				0 => '2',
				'id' => '2',
				1 => '图片',
				'name' => '图片',
				2 => '图片',
				'simp' => '图片',
				3 => 'pic',
				'flag' => 'pic',
				4 => '0',
				'root' => '0',
				5 => '2',
				'sort' => '2',
				6 => '2',
				'parents' => '2' 
		),
		2 => array (
				0 => '3',
				'id' => '3',
				1 => '视频',
				'name' => '视频',
				2 => '视频',
				'simp' => '视频',
				3 => 'video',
				'flag' => 'video',
				4 => '0',
				'root' => '0',
				5 => '3',
				'sort' => '3',
				6 => '3',
				'parents' => '3' 
		),
		3 => array (
				0 => '4',
				'id' => '4',
				1 => '活动',
				'name' => '活动',
				2 => '活动',
				'simp' => '活动',
				3 => 'hd',
				'flag' => 'hd',
				4 => '1',
				'root' => '1',
				5 => '1',
				'sort' => '1',
				6 => '1,4',
				'parents' => '1,4' 
		),
		4 => array (
				0 => '5',
				'id' => '5',
				1 => '公告',
				'name' => '公告',
				2 => '公告',
				'simp' => '公告',
				3 => 'gg',
				'flag' => 'gg',
				4 => '1',
				'root' => '1',
				5 => '2',
				'sort' => '2',
				6 => '1,5',
				'parents' => '1,5' 
		),
		5 => array (
				0 => '6',
				'id' => '6',
				1 => '活动1',
				'name' => '活动1',
				2 => '活动1',
				'simp' => '活动1',
				3 => 'hd1',
				'flag' => 'hd1',
				4 => '4',
				'root' => '4',
				5 => '1',
				'sort' => '1',
				6 => '1,4,6',
				'parents' => '1,4,6' 
		),
		6 => array (
				0 => '7',
				'id' => '7',
				1 => '活动2',
				'name' => '活动2',
				2 => '活动2',
				'simp' => '活动2',
				3 => 'hd2',
				'flag' => 'hd2',
				4 => '4',
				'root' => '4',
				5 => '2',
				'sort' => '2',
				6 => '1,4,7',
				'parents' => '1,4,7' 
		),
		7 => array (
				0 => '8',
				'id' => '8',
				1 => '活动11',
				'name' => '活动11',
				2 => '活动11',
				'simp' => '活动11',
				3 => 'hd11',
				'flag' => 'hd11',
				4 => '6',
				'root' => '6',
				5 => '1',
				'sort' => '1',
				6 => '1,4,6,8',
				'parents' => '1,4,6,8' 
		) 
);

foreach ( array_keys ( $list ) as $key ) {
	if ($list [$key] ['root'] == 0) {
		continue;
	}
	if (putChild ( $list, $list [$key] )) {
		unset ( $list [$key] );
	}
}
function putChild(&$list, $tree) {
	if (empty ( $list )) {
		return false;
	}
	foreach ( $list as $key => $val ) {
		if ($tree ['root'] == $val ['id'] && $tree ['id'] != $tree ['root']) {
			$list [$key] ['child'] [] = $tree;
			return true;
		}
		if (isset ( $val ['child'] ) && is_array ( $val ['child'] ) && ! empty ( $val ['child'] )) {
			if (putChild ( $list [$key] ['child'], $tree )) {
				return true;
			}
		}
	}
	return false;
}
echo "<pre>";
print_r ( $list );