<?php
set_time_limit(0);
$alldata = file_get_contents('./date');
$changcidata = file_get_contents('./changci');
$teamdata = file_get_contents('./team');

$changci_tmp = explode("\r\n", $changcidata);
$changci_arr = [];
foreach($changci_tmp as $val) {
    list($id, $name, $date) = explode(",", $val);
    $changci_arr[$id] = ['id'=>$id, 'name'=>$name, 'date'=>$date];
}

$team_tmp = explode("\r\n", $teamdata);
$team_arr = [];
foreach($team_tmp as $val) {
    list($name, $id) = explode(",", $val);
    $team_arr[$id] = ['id'=>$id, 'name'=>$name];
}

// var_dump($team_arr);exit;
$all_tmp = explode("\r\n", $alldata);
$all_arr = [];
foreach($all_tmp as $val) {
    list($teamid, $username, $score, $maxscore, $eatnum, $disnum, $deadnum, $ismvp, $date, $changciid) = explode(",", $val);
    $all_arr[$changciid][$teamid][] = ['teamname'=>$team_arr[$teamid]['name'], 'username'=>$username, 'score'=>$score, 'maxscore'=>$maxscore, 'eatnum'=>$eatnum, 'disnum'=>$disnum, 'deadnum'=>$deadnum, 'ismvp'=>$ismvp];
}

// $changciidget = $_REQUEST['changci'];


foreach ($changci_arr as $val) {
    $data = $all_arr[$val['id']];
    
    $str = '';
    foreach ($data as $value) {
        $str .= "队伍,玩家,积分,最大积分,吞噬数,吃掉的刺球数,死亡次数,是否是mvp\r\n";
        $str .= implode(",", $value[0])."\r\n";
        $str .= implode(",", $value[1])."\r\n";
        $str .= implode(",", $value[2])."\r\n";
        $str .= implode(",", $value[3])."\r\n";
        $str .= implode(",", $value[4])."\r\n";
        $str .= "\r\n";
    }
    file_put_contents('./test/'.iconv('UTF-8', 'GBK', $val['date']).'-'.iconv('UTF-8', 'GBK', $val['name']).'.csv', iconv('UTF-8', 'GBK', $str));
}

// _exportCSV($changci_arr[$changciidget]['date'].'-'.$changci_arr[$changciidget]['name'], $str);

function _exportCSV($filename, $data){
    // 		$data = iconv('utf-8','gbk', $data);
    $data = "\xEF\xBB\xBF".$data;

    header("Content-type:text/csv;charset=utf-8");
    header("Content-Disposition:attachment;filename=".$filename.'.csv');
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
    echo $data;
}