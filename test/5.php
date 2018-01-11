$str = file_get_contents('./5.txt');
$arr = explode("\r\n",  $str);
// var_dump($arr);exit;
$teamids = [];
foreach ($arr as $key=>$v){
    if ($key%2 == 0) {
        $teamids[] = (int)trim($v, '"');
    }
}
$tmp = implode("\r\n", $teamids);
file_put_contents('./5team.csv', $tmp);
var_dump($teamids);exit;
