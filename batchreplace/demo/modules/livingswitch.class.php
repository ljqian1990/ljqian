<?php

class PlgDemoLivingswitch implements IPlugin
{

    const MEMCACHE_SWITCH_KEY = 'balls_demo_livingswitch';

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
        
        $model = new CDemolivingswitch($this->site_id);
        $where .= " order by `sort` desc,`id` desc";
        $total = $model->countBy($where);
        $size = 15;
        $page = new Page($total, $size);
        
        $list = $model->listBy($where, $size, $page->offset());
        
        $this->view['curr_site_id'] = $this->site_id;
        $this->view['model'] = $_GET['model'];
        $this->view['list'] = Func::xnop($list, $size);
        $this->view['navpage'] = $page->make(null, 5, 'bluepage');
    }

    /**
     * 编辑banner
     */
    public function edit()
    {
        $id = Func::p('id');
        
        $model = new CDemolivingswitch($this->site_id);
        
        if (! empty($id)) {
            $model->load("`id`='{$id}'");
        } else {
            $model->state = 1;
        }
        $model->fish = Func::p('fish');
        $model->title = Func::p('title');
        $model->url = Func::p('url');
        $model->nexttime = Func::p('nexttime');
        $model->isiframe = Func::p('isiframe');
        $model->time = date("Y-m-d H:i:s");
        
        $resp['returnflag'] = 'OK';
        
        try {
            $model->save();
            
            $resp['fish'] = $model->fish;
            $resp['title'] = $model->title;
            $resp['url'] = $model->url;
            $resp['nexttime'] = $model->nexttime;
            $resp['isiframe'] = $model->isiframe;
            $resp['time'] = date("Y-m-d", strtotime($model->time));
        } catch (Exception $e) {
            $resp['returnflag'] = 'ERROR';
            $resp['message'] = $e->getMessage();
        }
        
        $this->clearCache();
        echo json_encode($resp);
    }

    public function editState()
    {
        $id = Func::p('id');
        $state = intval(Func::p('state'));
        
        if (empty($id) || empty($state)) {
            echo json_encode(array(
                'returnflag' => 'ERROR',
                'message' => '数据异常，id不能为空'
            ));
            exit();
        }
        
        $model = new CDemolivingswitch($this->site_id);
        $model->load("`id`='{$id}'");
        $model->state = $state;
        
        $resp['returnflag'] = 'OK';
        try {
            $model->save();
        } catch (Exception $ex) {
            $resp['returnflag'] = 'ERROR';
            $resp['message'] = $e->getMessage();
        }
        
        $this->clearCache();
        echo json_encode($resp);
    }

    /**
     * 删除banner
     */
    public function del()
    {
        $id = Func::p('del_id');
        if (empty($id)) {
            $resp['returnflag'] = 'NO_ID';
            $resp['message'] = 'no id assign';
        } else {
            $model = new CDemolivingswitch($this->site_id);
            try {
                $model->deleteByIDs($id);
                $this->clearCache();
                $resp['returnflag'] = 'OK';
            } catch (Exception $e) {
                $resp['returnflag'] = 'DB_ERRER';
                $resp['message'] = $e->getMessage();
            }
        }
        
        echo json_encode($resp);
    }

    /**
     * 更改banner的显示顺序
     */
    public function zort()
    {
        $oid = Func::p('oid');
        $osort = Func::p('osort');
        $nid = Func::p('nid');
        $nsort = Func::p('nsort');
        
        $model = new CDemolivingswitch($this->site_id);
        try {
            $model->updateBy(array(
                'id' => $oid,
                'sort' => $nsort
            ));
            $model->updateBy(array(
                'id' => $nid,
                'sort' => $osort
            ));
            $this->clearCache();
            $resp['returnflag'] = 'OK';
        } catch (Exception $e) {
            $resp['returnflag'] = 'DB_ERRER';
            $resp['message'] = $e->getMessage();
        }
        
        echo json_encode($resp);
    }

    public function form()
    {
        $id = Func::p('id');
        if (! empty($id)) {
            $model = new CDemolivingswitch($this->site_id);
            try {
                $this->_livingswitch = $model->infoById($id);
            } catch (Exception $e) {
                $resp['returnflag'] = 'ERROR';
                $resp['message'] = $e->getMessage();
                
                echo json_encode($resp);
            }
        }
        
        $isiframeselected = '';
        $isnotiframeselected = '';
        if ($this->_v('isiframe') == 1) {
            $isiframeselected = 'checked="checked"';
        } else {
            $isnotiframeselected = 'checked="checked"';
        }
        
        $html = <<<EOT
<div class="infoForm">
	<form action="?" method="post" onsubmit="return Livingswitch.save(this);">
		<input type="hidden" name="site_id" value="{$this->site_id}" />
		<input type="hidden" name="id" value="{$this->_v('id')}" />
		<table>
			<tr>
				<td class="ll" style="width: 80px;">标题</td>
				<td class="rr" colspan="5">
					<input type="text" name="title" value="{$this->_v('title')}" size="20" />
				</td>
			</tr>
			<tr>
				<td class="ll" style="width: 80px;">标识</td>
				<td class="rr" colspan="5">
					<input type="text" name="fish" value="{$this->_v('fish')}" size="20" />
				</td>
			</tr>
			<tr>
				<td class="ll" style="width: 80px;">直播地址</td>
				<td class="rr" colspan="5">
					<input type="text" name="url" value="{$this->_v('url')}" size="75" />
				</td>
			</tr>
			<tr>
				<td class="ll" style="width: 80px;">比赛提示</td>
				<td class="rr" colspan="5">
					<input type="text" name="nexttime" value="{$this->_v('nexttime')}" size="40" />
				</td>
			</tr>
			<tr>
				<td class="ll" style="width: 80px;">是否是iframe</td>
				<td class="rr" colspan="5">
					<input type="radio" id="isiframe" name="isiframe" value="1" {$isiframeselected}/><label for="isiframe">是</label>
					<input type="radio" id="isnotiframe" name="isiframe" value="0" {$isnotiframeselected}/><label for="isnotiframe">否</label>
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
<div>
EOT;
        
        $resp['html'] = $html;
        $resp['returnflag'] = 'OK';
        echo json_encode($resp);
        exit();
    }

    /**
     * 获取单条新闻的指定key的value值
     *
     * @param unknown $key            
     */
    private function _v($key)
    {
        return empty($this->_livingswitch) ? '' : (isset($this->_livingswitch[$key]) ? $this->_livingswitch[$key] : '');
    }

    private function clearCache()
    {
        $csite = new CSite();
        $site = $csite->infoById($this->site_id);
        $mc = Mc::getInstance(array(
            'host' => $site['mmhost'],
            'port' => $site['mmport']
        ));
        $mc->del(self::MEMCACHE_SWITCH_KEY);
    }
}