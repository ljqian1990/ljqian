<?php

class PlgYdmatchTeamdata implements IPlugin
{

    const MEMCACHE_TEAMDATA_KEY = 'balls_ydmatch_teamdata';

    const MEMCACHE_TEAMDATA_FORMVP_KEY = 'act_ydmatch_teamdata_for_mvp';
    
    const MEMCACHE_TEAMDATASUM_KEY = 'balls_ydmatch_teamdatasum';

    private $teamnameToId = [
        'FT' => 1,
        'SPR' => 2,
        'LK' => 3,
        'SOH' => 4,
        'AY' => 5,
        'FLY' => 6,
        'JOK' => 7,
        'OF' => 8,
        'CMD' => 9,
        'STS' => 10,
        'EOT' => 11,
        'TB' => 12,
        'CR' => 13,
        '619' => 14,
        'WT' => 15,
        'SC' => 16
    ];

    private $playerlist;
    // private $playerlist = [
    // '619 assa',
    // '619 bdao',
    // '619 bxian',
    // '619 star',
    // '619 xuan',
    // 'a lang',
    // 'abao',
    // 'annie',
    // 'anwen666',
    // 'apple',
    // 'asong',
    // 'ayak',
    // 'ayanna',
    // 'aydlong',
    // 'aymirs',
    // 'aypower',
    // 'beifeng',
    // 'beiyan',
    // 'belief',
    // 'cancan',
    // 'chen',
    // 'd',
    // 'dehua',
    // 'devil fu',
    // 'devil ku',
    // 'devil li',
    // 'devil lu',
    // 'devil ma',
    // 'dk2',
    // 'dy',
    // 'eku6',
    // 'flower',
    // 'gl',
    // 'guchen',
    // 'gumang',
    // 'heibai',
    // 'heilong',
    // 'honest',
    // 'honey',
    // 'hou1997',
    // 'jackson',
    // 'laowang',
    // 'latent',
    // 'leo',
    // 'lingyao',
    // 'lk aqi',
    // 'lk baiwn',
    // 'lk huba',
    // 'lk mumu',
    // 'lk tang',
    // 'ls',
    // 'luqiao',
    // 'mdd',
    // 'mingchen',
    // 'newwine',
    // 'nn',
    // 'ostyle',
    // 'pian',
    // 'pingping',
    // 'qidian',
    // 'quan',
    // 'sakura',
    // 'shaoye',
    // 'shihan',
    // 'six',
    // 'sunanli',
    // 'sup',
    // 'talang',
    // 'tigerbao',
    // 'tigerdy',
    // 'tigerfan',
    // 'tigernc',
    // 'tigeryi',
    // 'wanmei',
    // 'weiwei',
    // 'xsh98',
    // 'xuzhu',
    // 'yz',
    // 'zahara',
    // 'zhen0319',
    // 'zombile7'
    // ];
    public static function init()
    {}

    public function exec()
    {}

    public function shutdown()
    {}

    public function importScript()
    {
        $file = Func::g('f');
        
        header("Content-type: application/x-javascript");
        echo file_get_contents(dirname(__FILE__) . '/../scripts/' . $file);
    }

    public function index()
    {
        $where = '1';
        
        $model = new CYdmatchteamdata($this->site_id);
        $where .= " order by `id` desc";
        $total = $model->countBy($where);
        $size = 30;
        $page = new Page($total, $size);
        
        $data = $model->listBy($where, $size, $page->offset());
        
        $teamodel = new CYdmatchbanner($this->site_id);
        $teamlist = $teamodel->listBy('`fish`="team"', 100, 0);
        $teamarr = array();
        if (! empty($teamlist)) {
            foreach ($teamlist as $value) {
                $teamarr[$value['id']] = $value;
            }
        }
        
        if (! empty($data)) {
            foreach ($data as &$value) {
                $value['teamicon'] = $teamarr[$value['teamid']]['img'];
            }
        }
        
        $this->view['curr_site_id'] = $this->site_id;
        $this->view['model'] = $_GET['model'];
        $this->view['list'] = $data;
        $this->view['navpage'] = $page->make(null, 5, 'bluepage');
    }

    public function save()
    {
        $model = new CYdmatchteamdata($this->site_id);
        
        $data['nickname'] = Func::p('nickname');
        $data['playerid'] = Func::p('playerid');
        $data['teamid'] = Func::p('teamid');
        $data['icon'] = Func::p('icon');
        $data['score'] = Func::p('score');
        $data['maxscore'] = Func::p('maxscore');
        $data['eatnum'] = Func::p('eatnum');
        $data['deadnum'] = Func::p('deadnum');
        $data['dispersenum'] = Func::p('dispersenum');
        $data['mvpnum'] = Func::p('mvpnum');
        
        $id = Func::p('id');
        if (empty($id)) {
            $data['active'] = 0;
            $model->insertBy($data);
        } else {
            $data['id'] = $id;
            $model->updateBy($data);
        }        
        
        $resp['returnflag'] = 'OK';
        echo json_encode($resp);
        return true;
    }

    /**
     * 更改参赛成员的显示状态
     */
    public function active()
    {
        $id = Func::p('id');
        
        $model = new CYdmatchteamdata($this->site_id);
        $ret = $model->infoById($id);
        $active = ($ret['active'] == 1) ? 0 : 1;
        
        $model->load("`id`='{$id}'");
        $model->active = $active;
        
        $resp['returnflag'] = 'OK';
        $resp['flag'] = $active;
        try {
            $model->save();
            
            $this->clearCache();
        } catch (Exception $e) {
            $resp['returnflag'] = 'ERROR';
            $resp['message'] = $e->getMessage();
        }
        echo json_encode($resp);
    }

    /*
     * public function import()
     * {
     * $file = $_FILES['teamdata_file'];
     * $teamdata = file_get_contents($file['tmp_name']);
     *
     * $tmp_arr = explode("\r\n", $teamdata);
     * array_shift($tmp_arr);
     *
     * $model = new CYdmatchteamdata($this->site_id);
     * $ret = $model->infoBy('1=1');
     * if (empty($ret)) {
     * foreach ($tmp_arr as $val) {
     * if (! empty($val)) {
     * $mb = array();
     * list ($mb['id'], $mb['teamid'], $mb['teamname'], $mb['playerid'], $mb['nickname'], $mb['score'], $mb['maxscore'], $mb['eatnum'], $mb['dispersenum'], $mb['deadnum'], $mb['mvpnum']) = explode(',', $val);
     * if (! empty($mb['id'])) {
     * $mb['datetime'] = date('Y-m-d H:i:s');
     *
     * $model->insertBy($mb);
     * }
     * }
     * }
     * } else {
     * foreach ($tmp_arr as $val) {
     * if (! empty($val)) {
     * $mb = array();
     * list ($id, $mb['teamid'], $mb['teamname'], $mb['playerid'], $mb['nickname'], $mb['score'], $mb['maxscore'], $mb['eatnum'], $mb['dispersenum'], $mb['deadnum'], $mb['mvpnum']) = explode(',', $val);
     * if (! empty($id)) {
     * $model->updateBy($mb, '`id`=' . $id);
     * }
     * }
     * }
     * }
     *
     * header('Location: ?model=teamdata&plugin=Ydmatch&action=index');
     * }
     */
    public function import()
    {
        $this->setPlayerlist();
        
        list ($compdate, $changci) = explode(',', $_REQUEST['compdate']);
        $compday = $_REQUEST['compday'];
        $file = $_FILES['teamdata_file'];
        list ($matchInfo, $teamList) = $this->parseCSV($file['tmp_name']);
        $mvpplayer = $matchInfo['mvp']['1'];
        
        $model = new CYdmatchteamdatatotal($this->site_id);
        $smodel = new CYdmatchteamdatasum($this->site_id);
        
        $teamlistFromDB = $this->getTeamlist();
        
        $model->deleteByChangci($changci);
        $smodel->deleteByChangci($changci);
        
        foreach ($teamList as $teamname => $team) {
            foreach ($team['player'] as $player) {
                $mb = array();
                $mb['teamid'] = $this->getTeamidByName($teamname);
                $mb['changci'] = $changci;
                $mb['datetime'] = date('Y-m-d H:i:s');
                list ($teannametmp, $mb['playerid'], $mb['score'], $mb['maxscore'], $mb['eatnum'], $mb['dispersenum'], $mb['deadnum']) = explode(',', implode(',', $player));
                $mb['mvpnum'] = ($mb['playerid'] == $mvpplayer) ? 1 : 0;
                
                $this->replaceData('ydmatch_teamdatatotal', $mb, $model);
            }
            
            $mbs = array();
            $mbs['teamid'] = $this->getTeamidByName($teamname);
            $mbs['changci'] = $changci;
            $mbs['datetime'] = date('Y-m-d H:i:s');
            $mbs['group'] = $teamlistFromDB[$mbs['teamid']];
            list ($mbs['score'], $mbs['maxscore'], $mbs['eatnum'], $mbs['dispersenum'], $mbs['deadnum'], $mbs['first_disperse_player'], $mbs['first_disperse_time'], $mbs['max_score_player'], $mbs['max_score_player_num'], $mbs['max_disperse_player'], $mbs['max_disperse_player_num']) = explode(',', implode(',', $team['info']));
            $this->replaceData('ydmatch_teamdatasum', $mbs, $smodel);
        }
        
        // 更新teamdatasum表，结算小组积分
        $ssql = 'SELECT A.`teamid`,A.`id` FROM ydmatch_teamdatasum as A left join (select teamid,sum(`score`) as sumscore from ydmatch_teamdatasum group by `teamid` order by sumscore desc) as B on A.teamid=B.teamid WHERE changci =' . $changci . ' ORDER BY  `score` DESC,`sumscore` DESC';
        $datasumlist = $smodel->doQueryList($ssql, 4, 0);
        // $datasumlist = $smodel->listBy('`changci`=' . $changci . ' order by `score` desc', 4, 0);
        if (! empty($datasumlist)) {
            $smodel->updateBy(array(
                'groupscore' => 12
            ), '`id`=' . $datasumlist[0]['id']);
            $smodel->updateBy(array(
                'groupscore' => 5
            ), '`id`=' . $datasumlist[1]['id']);
            $smodel->updateBy(array(
                'groupscore' => 3
            ), '`id`=' . $datasumlist[2]['id']);
            $smodel->updateBy(array(
                'groupscore' => 2
            ), '`id`=' . $datasumlist[3]['id']);
        }
        
        $mvpmodel = new CYdmatchmvp($this->site_id);
        $mvpcount = $mvpmodel->countBy('1=1');
        // 导入mvp数据
        $md = array();
        $md['changci'] = $changci;
        $md['compday'] = $compday;
        $md['compdate'] = $compdate;
        $md['sort'] = intval($mvpcount) + 1;
        $md['datetime'] = date('Y-m-d H:i:s');
        list ($mvpteamname, $md['playerid'], $md['score'], $md['maxscore'], $md['eatnum'], $md['dispersenum'], $md['deadnum']) = explode(',', implode(',', $matchInfo['mvp']));
        $md['teamid'] = $this->getTeamidByName($mvpteamname);
        $this->replaceData('ydmatch_mvp', $md, $mvpmodel);
        
        // 同步teamdata表，队伍数据
        $sql = 'select `playerid`,ceil(AVG(`score`)) as per_score,MAX(`maxscore`) as max_maxscore,ceil(AVG(`eatnum`)) as per_eatnum,ceil(AVG(`dispersenum`)) as per_dispersenum,ceil(AVG(`deadnum`)) as per_deadnum,SUM(`mvpnum`) as sum_mvpnum from `ydmatch_teamdatatotal` GROUP BY `playerid`';
        $ret = $model->doQueryList($sql);
        if (! empty($ret)) {
            $datamodel = new CYdmatchteamdata($this->site_id);
            foreach ($ret as $val) {
                $datamodel->updateBy([
                    'score' => $val['per_score'],
                    'maxscore' => $val['max_maxscore'],
                    'eatnum' => $val['per_eatnum'],
                    'dispersenum' => $val['per_dispersenum'],
                    'deadnum' => $val['per_deadnum'],
                    'mvpnum' => $val['sum_mvpnum']
                ], '`playerid`="' . $val['playerid'] . '"');
            }
        }
        
        header('Location: ?model=teamdata&plugin=ydmatch&action=index');
    }

    private function setPlayerlist()
    {
        if (empty($this->playerlist)) {
            $model = new CYdmatchteamember($this->site_id);
            $ret = $model->listBy('1=1', 150, 0);
            $playerlist = [];
            if (! empty($ret)) {
                foreach ($ret as $value) {
                    $playerlist[] = trim(strtolower($value['nickname']));
                }
            }
            $this->playerlist = $playerlist;
        }
    }

    private function getTeamlist()
    {
        $teamodel = new CYdmatchbanner($this->site_id);
        $teamlist = $teamodel->listBy('`fish`="team"', 100, 0);
        $teamarr = array();
        if (! empty($teamlist)) {
            foreach ($teamlist as $value) {
                $teamarr[$value['id']] = trim($value['link']);
            }
        }
        return $teamarr;
    }

    private function replaceData($tablename, $data, $model)
    {
        $arrkey = array();
        $arrval = array();
        
        foreach ($data as $field => $value) {
            $arrkey[] = $field;
            $arrval[] = $value;
        }
        
        $strkey = implode("`,`", $arrkey);
        $strval = implode("','", $arrval);
        
        $sql = "replace into `{$tablename}` (`{$strkey}`) values('{$strval}')";
        $model->replaceData($sql);
    }

    private function parseCSV($filename)
    {
        $file = file_get_contents($filename);
        
        // 去除bom
        $file = trim($file, "\xEF\xBB\xBF");
        $dataLine = explode("\r", $file);
        
        // 所有的队伍名称清单，用于验证队伍名称是否有误
        $teamNameList = array_flip($this->teamnameToId);
        // 所有的选手ID清单，用于验证选手ID是否有误
        $playerIDList = $this->playerlist;
        // 标识队伍据的起始行，0时表示已经束结，5表示开始
        $lineStart = 0;
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
            if ($data[0] == '队伍') {
                // 初始化
                $currTeamInfo = [];
                $lineStart = 5;
                $result = preg_match("/.*积分:(\d+),最大积分:(\d+),吞噬数:(\d+),吃掉刺球数:(\d+),死亡次数:(\d+).*/", implode(',', $data), $match);
                if (! $result) {
                    throw new Exception('解析队伍积分时，发生异常#' . implode(',', $data));
                }
                $currTeamInfo['jifen'] = $match[1];
                $currTeamInfo['maxjifen'] = $match[2];
                $currTeamInfo['tunshi'] = $match[3];
                $currTeamInfo['ciqiu'] = $match[4];
                $currTeamInfo['dead'] = $match[5];
                continue;
            }
            
            // 大于0表示还有队员数据没有处理
            if ($lineStart > 0) {
                
                if (count($data) != 7) {
                    throw new Exception('选手个人数据异常，必须是7列数据#' . print_r($data, true));
                }
                
                foreach ($data as $k => $val) {
                    $data[$k] = $val = trim($val);
                }
                
                if ($data[0] === '' || $data[1] === '') {
                    throw new Exception('队伍名称或选手ID为空，请核实#' . print_r(implode(',', $data), true));
                }
                
                if (! preg_match("/^\d+$/", $data[2]) || ! preg_match("/^\d+$/", $data[3]) || ! preg_match("/^\d+$/", $data[4]) || ! preg_match("/^\d+$/", $data[5]) || ! preg_match("/^\d+$/", $data[6])) {
                    throw new Exception('选手数据异常，后5列内容必须为数字，请核实#' . print_r(implode(',', $data), true));
                }
                
                if (! in_array($data[0], $teamNameList)) {
                    throw new Exception('队伍名称错误，队伍清单中未找到该队伍名称#' . $data[0]);
                }
                
                // 将队员名字中的mvp取掉，并记录mvp数据
                $data[1] = str_replace('(mvp)', '', $data[1], $repCount);
                if ($repCount) {
                    $mvpData = $data;
                }
                
                if (! in_array(strtolower($data[1]), $playerIDList)) {
                    throw new Exception('选手ID错误，选手ID清单中未找到该选手ID#' . $data[1]);
                }
                
                $teamList[$data[0]]['player'][] = $data;
                $lineStart --;
                
                // 如果是当前队后的最后一行数据
                if ($lineStart == 0) {
                    // 队员数据的最后一行，一定是队伍的数据总结
                    $str = $dataLine[$line + 1];
                    $result = preg_match("/.*第一次炸刺:(.+)\((.+)\),吃对手分数最多:(.+)\((\d+)\),炸刺最多:(.+)\((\d+)\).*/", $str, $match);
                    if (! $result) {
                        throw new Exception('解析伍队炸刺、分数最多、炸刺最多时，发生异常#' . $str);
                    }
                    $currTeamInfo['first_zhaci_player'] = trim($match[1]);
                    $currTeamInfo['first_zhaci_time'] = trim($match[2]);
                    $currTeamInfo['max_jifen_player'] = trim($match[3]);
                    $currTeamInfo['max_jifen'] = trim($match[4]);
                    $currTeamInfo['max_zhaci_player'] = trim($match[5]);
                    $currTeamInfo['max_zhaci'] = trim($match[6]);
                    
                    $teamList[$data[0]]['info'] = $currTeamInfo;
                }
            }
            
            if ($data[0] == '全部选手总分数') {
                $matchData = explode(',', $dataLine[$line + 1]);
                
                if (count($matchData) != 4) {
                    throw new Exception('比赛汇总数据异常，必须是4列数据#' . print_r($matchData, true));
                }
                
                foreach ($matchData as $val) {
                    if ($val === '') {
                        throw new Exception('比赛汇总数据缺失，请核实#' . print_r(implode(',', $matchData), true));
                    }
                }
                
                $matchInfo['jifen_sum'] = $matchData[0];
                $matchInfo['ciqiu_sum'] = $matchData[1];
                $matchInfo['ko_max_player'] = $matchData[2];
                $matchInfo['ciqiu_max_player'] = $matchData[3];
                if (empty($mvpData)) {
                    throw new Exception('比赛数据异常，缺少MVP数据');
                }
                $matchInfo['mvp'] = $mvpData;
            }
        }
        
        return [
            $matchInfo,
            $teamList
        ];
    }

    private function getTeamidByName($teamname)
    {
        if (! isset($this->teamnameToId[$teamname])) {
            $teamnamelist = array_flip($this->teamnameToId);
            $teamnamestr = explode(',', $teamnamelist);
            throw new Exception($teamname . '这个战队缩写在配置文件中无法找到，请确定缩写是否正确。现有缩写包括' . $teamnamestr);
        }
        
        return $this->teamnameToId[$teamname];
    }

    public function form()
    {
        $id = Func::p('id');
        if (! empty($id)) {
            $model = new CYdmatchteamdata($this->site_id);
            try {
                $this->data = $model->infoById($id);
            } catch (Exception $e) {
                $resp['returnflag'] = 'ERROR';
                $resp['message'] = $e->getMessage();
                
                echo json_encode($resp);
            }
        }
        
        // 分类
        $teamodel = new CYdmatchbanner($this->site_id);
        $teamlist = $teamodel->listBy('`fish`="team"', 100, 0);
        $options = '';
        if (! empty($teamlist)) {
            foreach ($teamlist as $key => $val) {
                $options .= '<option label="' . $val['title'] . '" value="' . $val['id'] . '" ' . (($this->_v('teamid') == $val['id']) ? 'selected="selected"' : '') . '>' . $val['title'] . '</option>';
            }
        }
        
        $resp['html'] = '
 <div class="infoForm">
 <form action="?" method="post" onsubmit="return Teamdata.save(this);">
 <input type="hidden" name="site_id" value="' . $this->site_id . '" />
 <input type="hidden" name="id" value="' . $this->_v('id') . '" />
 <table>
 <tr>
 <td class="ll" style="width: 80px;">ID昵称</td>
 <td class="rr" colspan="5">
 <input type="text" name="nickname" value="' . $this->_v('nickname') . '" />
 </td>
 <td class="ll">选择队伍</td>
 <td class="rr" colspan="2" id="atypetd">
 <select name="teamid" >
 ' . $options . '
 </select>
 </td>
 </tr>
 <tr> 
 <td class="ll" style="width: 80px;">选手头像</td>
 <td class="rr" colspan="7">
 <input id="_uploadimg" type="text" name="icon" value="' . $this->_v('icon') . '" size="28" readonly />
 <button type="button" onmouseover="$.browpop(event,this,imgcoreback)" icon="folder-open" class="ui-button" title="浏览">浏览</button>
 <span style="color:red">*此功能无法自动生成缩略图，上传前请自行裁剪</span>
 </td> 
 </tr>
 <tr>
 <td class="ll" style="width: 80px;">英文标识符</td>
 <td class="rr">
 <input type="text" value="' . $this->_v('playerid') . '" name="playerid" />
 </td>
 <td class="ll" style="width: 80px;">场均积分</td>
 <td class="rr">
 <input type="text" value="' . $this->_v('score') . '" name="score" />
 </td>
 <td class="ll" style="width: 80px;">场均吞噬</td>
 <td class="rr" class="rr" colspan="5" style="padding-right: 0px;">
 <input type="text" name="eatnum" value="' . $this->_v('eatnum') . '" style="margin-right: 0; " />
 </td>
 </tr>
 <tr> 
 <td class="ll" style="width: 80px;">场均扎刺</td>
 <td class="rr" class="rr" style="padding-right: 0px;">
 <input type="text" name="dispersenum" value="' . $this->_v('dispersenum') . '" style="margin-right: 0; " />
 </td>
 <td class="ll" style="width: 80px;">MVP数</td>
 <td class="rr" class="rr" style="padding-right: 0px;">
 <input type="text" name="mvpnum" value="' . $this->_v('mvpnum') . '" style="margin-right: 0; " />
 </td>
 <td class="ll" style="width: 80px;">历史最大积分</td>
 <td class="rr" style="padding-right: 0px;">
 <input type="text" name="maxscore" value="' . $this->_v('maxscore') . '" style="margin-right: 0; " />
 </td>
 </tr>
 <tr> 
 <td class="ll" style="width: 80px;">场均死亡</td>
 <td class="rr" style="padding-right: 0px;">
 <input type="text" name="deadnum" value="' . $this->_v('deadnum') . '" style="margin-right: 0; " />
 </td>
 </tr>         
 <tr>
 <td colspan="8">
 <button type="submit" class="ui-button" icon="check" title="确定">确定</button>
 <button type="button" class="ui-button close" icon="close" title="取消">取消</button>
 </td>
 </tr>
 </table>
 </form>
 </div>';
        $resp['returnflag'] = 'OK';
        
        echo json_encode($resp);
        exit();
    }

    public function clearmc()
    {
        $this->clearCache();
        $resp['returnflag'] = 'OK';
        echo json_encode($resp);
    }

    /**
     * 获取单条新闻的指定key的value值
     *
     * @param unknown $key            
     */
    private function _v($key)
    {
        return empty($this->data) ? '' : (isset($this->data[$key]) ? $this->data[$key] : '');
    }

    private function clearCache()
    {
        $csite = new CSite();
        $site = $csite->infoById($this->site_id);
        $mc = Mc::getInstance(array(
            'host' => $site['mmhost'],
            'port' => $site['mmport']
        ));
        $mc->del(self::MEMCACHE_TEAMDATA_KEY);
        $mc->del(self::MEMCACHE_TEAMDATA_FORMVP_KEY);
        $mc->del(self::MEMCACHE_TEAMDATASUM_KEY);        
    }
}