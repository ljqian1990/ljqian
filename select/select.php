<?php
header("Content-type:text/html;charset=utf-8");

$site_str = '<select id="__header_nav_site" style="width: 158px; height: 20px;">
<option value="0">所有网站</option>
<option label="艾尔之光" value="60">艾尔之光</option>
<option label="SD敢达" value="59">SD敢达</option>
<option label="Vainglory" value="58">Vainglory</option>
<option label="大唐玄装" value="57">大唐玄装</option>
<option label="古龙群侠传" value="56">古龙群侠传</option>
<option label="巨人武侠" value="55">巨人武侠</option>
<option label="街头蓝球" value="54">街头蓝球</option>
<option label="征途穿越版" value="53">征途穿越版</option>
<option label="征途2动作专区" value="52">征途2动作专区</option>
<option label="征途3D" value="51">征途3D</option>
<option label="炫斗三国志" value="50">炫斗三国志</option>
<option label="狂野星球" value="49" selected="selected">狂野星球</option>
<option label="传奇" value="48">传奇</option>
<option label="仙侠世界2" value="47">仙侠世界2</option>
<option label="大主宰" value="46">大主宰</option>
<option label="武极天下" value="45">武极天下</option>
<option label="征途口袋" value="44">征途口袋</option>
<option label="征途2" value="42">征途2</option>
<option label="黑猫警长2" value="41">黑猫警长2</option>
<option label="大骑士传说" value="40">大骑士传说</option>
<option label="地下城传说" value="39">地下城传说</option>
<option label="征途无双" value="38">征途无双</option>
<option label="择天记" value="36">择天记</option>
<option label="主公不可以" value="33">主公不可以</option>
<option label="测试" value="32">测试</option>
<option label="FM一球成名" value="31">FM一球成名</option>
<option label="撸塔传奇" value="30">撸塔传奇</option>
<option label="巨人移动" value="29">巨人移动</option>
<option label="征途2经典版" value="28">征途2经典版</option>
<option label="中国好舞蹈" value="26">中国好舞蹈</option>
<option label="乱炖英雄" value="24">乱炖英雄</option>
<option label="江湖" value="23">江湖</option>
<option label="幻世" value="22">幻世</option>
<option label="苍天2" value="21">苍天2</option>
<option label="新征途" value="20">新征途</option>
<option label="战国破坏神" value="19">战国破坏神</option>
<option label="征途怀旧" value="17">征途怀旧</option>
<option label="绿色征途" value="16">绿色征途</option>
<option label="仙侠世界" value="15">仙侠世界</option>
<option label="万神" value="14">万神</option>
<option label="仙境江湖" value="12">仙境江湖</option>
<option label="游戏官网简化版" value="10">游戏官网简化版</option>
<option label="游戏官网标准版" value="9">游戏官网标准版</option>
<option label="征途免费版" value="7">征途免费版</option>
<option label="绒绒大战" value="4">绒绒大战</option>
<option label="三国战魂" value="3">三国战魂</option>
<option label="巫师之怒" value="1">巫师之怒</option>
</select>';

$site_arr = explode("\r", $site_str);
$site_arr_tmp = array();
foreach ($site_arr as $sitestr){
	preg_match('/label="(.*?)" value="(.*?)"/', $sitestr, $matches);
	if (!empty($matches)){
		$site_arr_tmp[] = array('name'=>$matches[1], 'value'=>$matches[2]);
	}
	
}

// var_export($site_arr_tmp);

$site_arr = array ( 0 => array ( 'name' => '艾尔之光', 'value' => '60', ), 1 => array ( 'name' => 'SD敢达', 'value' => '59', ), 2 => array ( 'name' => 'Vainglory', 'value' => '58', ), 3 => array ( 'name' => '大唐玄装', 'value' => '57', ), 4 => array ( 'name' => '古龙群侠传', 'value' => '56', ), 5 => array ( 'name' => '巨人武侠', 'value' => '55', ), 6 => array ( 'name' => '街头蓝球', 'value' => '54', ), 7 => array ( 'name' => '征途穿越版', 'value' => '53', ), 8 => array ( 'name' => '征途2动作专区', 'value' => '52', ), 9 => array ( 'name' => '征途3D', 'value' => '51', ), 10 => array ( 'name' => '炫斗三国志', 'value' => '50', ), 11 => array ( 'name' => '狂野星球', 'value' => '49', ), 12 => array ( 'name' => '传奇', 'value' => '48', ), 13 => array ( 'name' => '仙侠世界2', 'value' => '47', ), 14 => array ( 'name' => '大主宰', 'value' => '46', ), 15 => array ( 'name' => '武极天下', 'value' => '45', ), 16 => array ( 'name' => '征途口袋', 'value' => '44', ), 17 => array ( 'name' => '征途2', 'value' => '42', ), 18 => array ( 'name' => '黑猫警长2', 'value' => '41', ), 19 => array ( 'name' => '大骑士传说', 'value' => '40', ), 20 => array ( 'name' => '地下城传说', 'value' => '39', ), 21 => array ( 'name' => '征途无双', 'value' => '38', ), 22 => array ( 'name' => '择天记', 'value' => '36', ), 23 => array ( 'name' => '主公不可以', 'value' => '33', ), 24 => array ( 'name' => '测试', 'value' => '32', ), 25 => array ( 'name' => 'FM一球成名', 'value' => '31', ), 26 => array ( 'name' => '撸塔传奇', 'value' => '30', ), 27 => array ( 'name' => '巨人移动', 'value' => '29', ), 28 => array ( 'name' => '征途2经典版', 'value' => '28', ), 29 => array ( 'name' => '中国好舞蹈', 'value' => '26', ), 30 => array ( 'name' => '乱炖英雄', 'value' => '24', ), 31 => array ( 'name' => '江湖', 'value' => '23', ), 32 => array ( 'name' => '幻世', 'value' => '22', ), 33 => array ( 'name' => '苍天2', 'value' => '21', ), 34 => array ( 'name' => '新征途', 'value' => '20', ), 35 => array ( 'name' => '战国破坏神', 'value' => '19', ), 36 => array ( 'name' => '征途怀旧', 'value' => '17', ), 37 => array ( 'name' => '绿色征途', 'value' => '16', ), 38 => array ( 'name' => '仙侠世界', 'value' => '15', ), 39 => array ( 'name' => '万神', 'value' => '14', ), 40 => array ( 'name' => '仙境江湖', 'value' => '12', ), 41 => array ( 'name' => '游戏官网简化版', 'value' => '10', ), 42 => array ( 'name' => '游戏官网标准版', 'value' => '9', ), 43 => array ( 'name' => '征途免费版', 'value' => '7', ), 44 => array ( 'name' => '绒绒大战', 'value' => '4', ), 45 => array ( 'name' => '三国战魂', 'value' => '3', ), 46 => array ( 'name' => '巫师之怒', 'value' => '1', ), );

require_once '../pdo/mysqlpdo/mysql.class.php';
$db = new mysqlDB();
foreach ($site_arr as $value){
	$db->insert('site', array('siteid'=>$value['value'], 'sitename'=>$value['name']));
}




var_dump($site_arr);
