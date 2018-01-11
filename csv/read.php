<?php
$teamnametoid = [
    'LEA' => 1,
    'TWS' => 2,
    'EDGE' => 3,
    'FOX' => 4,
    'BST' => 5,
    'TY' => 6,
    'VR' => 7,
    'RBT' => 8,
    'EOT' => 9,
    'AY' => 10,
    'CR' => 11,
    'CMD' => 12,
    'TB' => 13,
    'FT' => 14,
    'JOKER' => 15,
    'LK' => 16,
    'SC' => 17,
    'SPR' => 18,
    'STS' => 19,
    'SY' => 20,
    'OF' => 21,
    'SPG' => 22,
    'HUYA-619' => 23,
    'FLY' => 24,
    'KGirls' => 38,
    'ALG' => 39,
    'RF' => 40,
    'PAPA' => 41
];
$playerlist = [
    'ostyle',
    'pian',
    'jokbao',
    'ls',
    'key',
    'lk tang',
    'lk huba',
    'lk aqi',
    'lk mumu',
    'lk putao',
    'huya619_assa',
    'huya619_star',
    'huya619_bdao',
    'huya619_xuan',
    'huya619_bxian',
    'sup',
    'gumang',
    'mingchen',
    'moon',
    'ychen',
    'fox_wolf',
    'fox_monkey',
    'fox_leopard',
    'fox_rabbit',
    'fox_snake',
    'mu',
    'xiang',
    'lang',
    'lun',
    'chen'
];
$ret = parseCSV('./data.csv', $teamnametoid, $playerlist);

function parseCSV($filename, $teamnametoid, $playerlist)
{
    $file = file_get_contents($filename);
    
    // 去除bom
    $file = trim($file, "\xEF\xBB\xBF");
    $dataLine = explode("\r", $file);
    
    // 所有的队伍名称清单，用于验证队伍名称是否有误
    $teamNameList = array_flip($teamnametoid);
    // 所有的选手ID清单，用于验证选手ID是否有误
    $playerIDList = $playerlist;
    // 标识队伍据的起始行，0时表示已经束结，5表示开始
    // $lineStart = 0;
    // 所有队伍数据
    $teamList = [];
    // mvp数据
    $mvpData = [];
    // 临时变量，放队伍数据
    $currTeamInfo = [];
    // 本场比赛的数据
    $matchInfo = [];
    
    foreach ($dataLine as $line => $data) {
        $data = explode(',', $data);
        
        // 如果本行以队伍开头，则表示是一个队伍数据的开始
        if (trim($data[0]) == '队伍' || trim($data[1]) == '队伍统计' || (empty($data[0]) && empty($data[1]))) {        
            continue;
        }               
        
        // 大于0表示还有队员数据没有处理        
        if (count($data) != 19) {var_dump($data);exit;
            throw new Exception('选手个人数据异常，必须是19列数据#' . print_r($data, true));
        }
        
        foreach ($data as $k => $val) {
            $data[$k] = $val = trim($val);
        }
        
        if ($data[0] === '' || $data[1] === '') {
            throw new Exception('队伍名称或选手ID为空，请核实#' . print_r(implode(',', $data), true));
        }
        
        if (! preg_match("/^\d+$/", $data[2]) || ! preg_match("/^\d+$/", $data[4]) || ! preg_match("/^\d+$/", $data[5]) || ! preg_match("/^\d+$/", $data[6]) || ! preg_match("/^\d+$/", $data[7])) {
            throw new Exception('选手数据异常，积分、最大积分、吞噬数、吃掉的刺球数、死亡次数必须为数字，请核实#' . print_r(implode(',', $data), true));
        }
        
        if (! in_array(strtoupper($data[0]), $teamNameList)) {
            throw new Exception('队伍名称错误，队伍清单中未找到该队伍名称#' . $data[0]);
        }

        if (! in_array(strtolower($data[1]), $playerIDList)) {
            throw new Exception('选手ID错误，选手ID清单中未找到该选手ID#' . $data[1]);
        }
        
        // 将队员名字中的mvp取掉，并记录mvp数据
        $playerdata = [$data[0], $data[1], $data[2], $data[4], $data[5], $data[6], $data[7]];
        if ($data[18] == 1) {
            $mvpData = $playerdata;
        }
        
        $teamList[$data[0]]['player'][] = $playerdata;
        
    }
    if (empty($mvpData)) {
        throw new Exception('比赛数据异常，缺少MVP数据');
    }
    $matchInfo['mvp'] = $mvpData;
    
    return [
        $matchInfo,
        $teamList
    ];
}