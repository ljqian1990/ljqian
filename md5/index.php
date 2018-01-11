<?php
$arr = ['balls_act_cest_gameid_'.md5('大毛哥'), 'balls_act_cest_gameid_'.md5('毁约'), 'balls_act_cest_gameid_'.md5('迷糊叔'), 'balls_act_cest_gameid_'.md5('Fansky'), 'balls_act_cest_gameid_'.md5('最美糖糖'), 'balls_act_cest_gameid_'.md5('泡泡517')];




$dp = dir('member');
$data = '';
while ($file = $dp->read()) {
	if($file !="." && $file !="..") {
		$file_arr = explode('.', $file);
		$filename = iconv('GBK', 'utf-8', $file_arr[0]);
		echo $filename.'=>'.md5($filename).PHP_EOL;		
		
		$filename_md5 = substr(md5($filename), 0, 10);
		$data .= $filename_md5.'=>'.$filename.PHP_EOL;
		copy('member/'.$file, 'member3/'.$filename_md5.'.jpg');
	}
}
$dp->close();

file_put_contents('map2', $data);