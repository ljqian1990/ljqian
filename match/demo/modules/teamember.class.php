<?php

class PlgYdmatchTeamember implements IPlugin
{

    const MEMCACHE_TEAMEMBER_LIST_KEY = 'balls_ydmatch_teamember_list';

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

    /**
     * 显示新闻列表
     */
    public function index()
    {
        $size = 30;
        
        $model = new CYdmatchteamember($this->site_id);
        
        $where = "1";
        
        $total = $model->countBy($where);
        
        $page = new Page($total, $size);
        
        $where .= " order by `sort` desc,`id` desc";
        
        $list = $model->listBy($where, $size, $page->offset());
        
        $teamodel = new CYdmatchbanner($this->site_id);
        $teamlist = $teamodel->listBy('`fish`="team"', 100, 0);
        $teamarr = array();
        if (! empty($teamlist)) {
            foreach ($teamlist as $value) {
                $teamarr[$value['id']] = $value;
            }
        }
        
        if (! empty($list)) {
            foreach ($list as &$value) {
                $value['teamicon'] = $teamarr[$value['teamid']]['img'];
            }
        }
        
        $smarty = SmartyEx::init();
        $smarty->assign("list", Func::xnop($list, $size));
        $smarty->assign('model', $_GET['model']);
        $smarty->assign('navpage', $page->make(null, 5, 'bluepage'));
        $smarty->assign('curr_site_id', $this->site_id);
    }

    /**
     * 编辑新闻内容
     */
    public function edit()
    {
        $id = Func::p('id');
        if (empty($id)) {
            $this->save('insertBy');
        } else {
            $this->save('updateBy');
        }
    }

    /**
     * 保存新闻方法实现
     * 保存新闻方法实现
     *
     * @param
     *            string 执行方法
     * @return bool
     */
    private function save($exec)
    {
        if (! Func::isPost()) {
            return false;
        }
        // ** 新闻对象
        $model = new CYdmatchteamember($this->site_id);
        $data['name'] = Func::p('name');
        $data['teamid'] = Func::p('teamid');
        $data['nickname'] = Func::p('nickname');
        $data['totalwinnernum'] = Func::p('totalwinnernum');
        $data['mvpnum'] = Func::p('mvpnum');
        $data['eatnum'] = Func::p('eatnum');
        $data['fansnum'] = Func::p('fansnum');
        $data['icon'] = Func::p('icon');
        $data['datetime'] = time();
        
        // ** 空ID新增
        $id = Func::p('id');
        if (empty($id)) {
            $max_sort_data = $model->infoBy('1 order by sort desc');
            $data['sort'] = (int) $max_sort_data['sort'] + 1;
        } else {
            $data['id'] = $id;
        }
        
        // ** 保存
        $succ = $model->$exec($data);
        
        // ** 若保存失败
        if (! $succ) {
            $resp['returnflag'] = 'DB_ERROR';
            echo json_encode($resp);
            return false;
        }
        
        $resp['returnflag'] = 'OK';
        echo json_encode($resp);
        return true;
    }

    /**
     * 删除新闻信息
     */
    public function del()
    {
        $id = Func::p('del_id');
        if (empty($id)) {
            $resp['returnflag'] = 'NO_ID';
            $resp['message'] = 'no id assign';
        } else {
            $model = new CYdmatchteamember($this->site_id);
            try {
                $model->deleteByIDs($id);
                $resp['returnflag'] = 'OK';
            } catch (Exception $e) {
                $resp['returnflag'] = 'DB_ERRER';
                $resp['message'] = $e->getMessage();
            }
        }
        
        echo json_encode($resp);
    }

    /**
     * 更改新闻排序
     */
    public function zort()
    {
        $oid = Func::p('oid');
        $osort = Func::p('osort');
        $nid = Func::p('nid');
        $nsort = Func::p('nsort');
        
        $model = new CYdmatchteamember($this->site_id);
        try {
            $model->updateBy(array(
                'id' => $oid,
                'sort' => $nsort
            ));
            $model->updateBy(array(
                'id' => $nid,
                'sort' => $osort
            ));
            $resp['returnflag'] = 'OK';
        } catch (Exception $e) {
            $resp['returnflag'] = 'DB_ERRER';
            $resp['message'] = $e->getMessage();
        }
        
        echo json_encode($resp);
    }

    public function import()
    {
        $file = $_FILES['teamember_file'];
        $teamdata = file_get_contents($file['tmp_name']);
        
        $tmp_arr = explode("\r\n", $teamdata);
        array_shift($tmp_arr);
        
        $model = new CYdmatchteamember($this->site_id);
        $ret = $model->infoBy('1=1');
        if (empty($ret)) {
            $sort = 1;
            foreach ($tmp_arr as $val) {
                if (! empty($val)) {
                    $mb = array();
                    list ($mb['id'], $mb['name'], $mb['teamid'], $mb['nickname'], $mb['totalwinnernum'], $mb['mvpnum'], $mb['eatnum'], $mb['fansnum']) = explode(',', $val);
                    if (! empty($mb['id'])) {
                        $mb['datetime'] = date('Y-m-d H:i:s');
                        $mb['sort'] = $sort;
                        $model->insertBy($mb);
                        $sort ++;
                    }
                }
            }
        } else {
            foreach ($tmp_arr as $val) {
                if (! empty($val)) {
                    $mb = array();
                    list ($id, $mb['name'], $mb['teamid'], $mb['nickname'], $mb['totalwinnernum'], $mb['mvpnum'], $mb['eatnum'], $mb['fansnum']) = explode(',', $val);
                    if (! empty($id)) {
                        $model->updateBy($mb, '`id`=' . $id);
                    }
                }
            }
        }
        
        header('Location: ?model=teamember&plugin=ydmatch&action=index');
    }

    /**
     */
    public function form()
    {
        $id = Func::p('id');
        if (! empty($id)) {
            $model = new CYdmatchteamember($this->site_id);
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
 <form action="?" method="post" onsubmit="return Teamember.save(this);">
 <input type="hidden" name="site_id" value="' . $this->site_id . '" />
 <input type="hidden" name="id" value="' . $this->_v('id') . '" />
 <table>
 <tr>
 <td class="ll" style="width: 80px;">选手名字</td>
 <td class="rr" colspan="5">
 <input type="text" name="name" value="' . $this->_v('name') . '" />
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
 <td class="ll" style="width: 80px;">昵称</td>
 <td class="rr" style="padding-right: 0px;">
 <input type="text" name="nickname" value="' . $this->_v('nickname') . '" style="margin-right: 0; " />
 </td>
 <td class="ll" style="width: 80px;">总冠军数</td>
 <td class="rr" class="rr" colspan="5" style="padding-right: 0px;">
 <input type="text" name="totalwinnernum" value="' . $this->_v('totalwinnernum') . '" style="margin-right: 0; " />
 </td>
 </tr>
 <tr>
 <td class="ll" style="width: 80px;">mvp数</td>
 <td class="rr" style="padding-right: 0px;">
 <input type="text" name="mvpnum" value="' . $this->_v('mvpnum') . '" style="margin-right: 0; " />
 </td>
 <td class="ll" style="width: 80px;">吞噬数</td>
 <td class="rr" class="rr" colspan="5" style="padding-right: 0px;">
 <input type="text" name="eatnum" value="' . $this->_v('eatnum') . '" style="margin-right: 0; " />
 </td>
 </tr>
 <tr>
 <td class="ll" style="width: 80px;">粉丝数</td>
 <td class="rr" style="padding-right: 0px;">
 <input type="text" name="fansnum" value="' . $this->_v('fansnum') . '" style="margin-right: 0; " />
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

    /**
     * 更改参赛成员的显示状态
     */
    public function active()
    {
        $id = Func::p('id');
        
        $model = new CYdmatchteamember($this->site_id);
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
        $mc->del(self::MEMCACHE_TEAMEMBER_LIST_KEY);
    }
}

?>