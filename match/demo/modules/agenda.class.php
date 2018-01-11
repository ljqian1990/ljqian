<?php
class PlgYdmatchAgenda implements IPlugin {
	const MEMCACHE_AGENDA_KEY = 'balls_ydmatch_agenda';
	
	private $_agenda;
	
	const GROUP_VIDEO_NUM = 5;
	
	public static function init() {
	}
	public function exec() {
	}
	public function shutdown() {
	}
	public function importScript() {
		$file = Func::g ( 'f' );
		
		header ( "Content-type: application/x-javascript" );
		echo file_get_contents ( dirname ( __FILE__ ) . '/../scripts/' . $file );
	}
	public function index() {		
		$model = new CYdmatchagenda($this->site_id);
		$where = "1=1 order by `sort` desc, `id` asc";
		$list = $model->listBy($where);
		if (!empty($list)) {
			foreach ($list as &$val) {
				$val['group'] = empty($val['data']) ? array() : unserialize($val['data']);
			}
		}
		
		$this->view['curr_site_id'] = $this->site_id;
		$this->view['model'] = $_GET['model'];
		$this->view ['list'] = $list;
	}
	
	public function form()
	{
		$id = Func::p('id');
		if (!empty($id)) {
			$model = new CYdmatchagenda($this->site_id);
			try {
				$this->_agenda = $model->infoById ( $id );
			} catch ( Exception $e ) {
				$resp ['returnflag'] = 'ERROR';
				$resp ['message'] = $e->getMessage ();
			
				echo json_encode ( $resp );
			}
		}
		
		$html = <<<EOT
<div class="infoForm">
	<form action="?" method="post" onsubmit="return Agenda.save(this);">
		<input type="hidden" name="site_id" value="{$this->site_id}" />
		<input type="hidden" name="id" value="{$this->_v('id')}" />
		<table>
			<tr>
				<td class="ll" style="width: 80px;">赛程时间</td>
				<td class="rr" colspan="5">
					<input class="_datepicker" type="text" name="date" size="12" value="{$this->_v('date')}" size="75" />
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
		echo json_encode ( $resp );exit;
	}
	
	/**
	 * 获取单条新闻的指定key的value值
	 *
	 * @param unknown $key
	 */
	private function _v($key)
	{
		return empty ( $this->_agenda ) ? '' : (isset ( $this->_agenda[$key] ) ? $this->_agenda[$key] : '');
	}
	
	/**
	 * 编辑banner
	 */
	public function edit()
	{
		$id   = Func::p('id');
		if (empty($id)){
			$this->save('insertBy');
		}else{
			$this->save('updateBy');
		}
	}
	
	private function save($exec)
	{
		if (!Func::isPost()) {
			return false;
		}
		// ** 新闻对象
		$model = new CYdmatchagenda($this->site_id);
		// ** 数据
		$data['date']   = Func::p('date');
		
		// ** 空ID新增
		$id   = Func::p('id');
		
		if (empty($id)) {		
			//添加排序
			$max_sort_data = $model->infoBy ( '1 order by sort desc' );
			$data['sort'] = intval($max_sort_data['sort']) + 1;
		} else {
			$data['id']       = $id;
		}
		
		// ** 保存
		$succ = $model->$exec($data);
		
		$tmpid = empty($id) ? $succ : $id;
		$this->clearCache();
		
		// ** 若保存失败
		if (!$succ) {
			$resp['returnflag'] = 'DB_ERROR';
			echo json_encode($resp);
			return false;
		}
		
		$resp['returnflag'] = 'OK';
		echo json_encode($resp);
		return true;
	}
	
	/**
	 * 删除banner
	 */
	public function del() {
		$id = Func::p ( 'del_id' );
		if (empty ( $id )) {
			$resp ['returnflag'] = 'NO_ID';
			$resp ['message'] = 'no id assign';
		} else {
			$model = new CYdmatchagenda ( $this->site_id );
			try {
				$model->deleteByIDs ( $id );
				$resp ['returnflag'] = 'OK';
			} catch ( Exception $e ) {
				$resp ['returnflag'] = 'DB_ERRER';
				$resp ['message'] = $e->getMessage ();
			}
		}
		
		$this->clearCache($id);
		echo json_encode ( $resp );
	}
	
	/**
	 * 更改banner的显示顺序
	 */
	public function zort() {
		$oid = Func::p ( 'oid' );
		$osort = Func::p ( 'osort' );
		$nid = Func::p ( 'nid' );
		$nsort = Func::p ( 'nsort' );
		
		$model = new CYdmatchagenda( $this->site_id );
		try {
			$model->updateBy ( array (
					'id' => $oid,
					'sort' => $nsort
			) );
			$model->updateBy ( array (
					'id' => $nid,
					'sort' => $osort
			) );
			$resp ['returnflag'] = 'OK';
		} catch ( Exception $e ) {
			$resp ['returnflag'] = 'DB_ERRER';
			$resp ['message'] = $e->getMessage ();
		}
		
		$this->clearCache();
		echo json_encode ( $resp );
	}
	
	/**
	 * 增加分组表单
	 */
	public function addGroupForm()
	{
		$id = Func::p('id');
		$gid = Func::p('gid');
		
		$groupdata = array('teams', 'videos', 'groupdesc', 'groupname', 'isover', 'riseteam');
		
		$model = new CYdmatchagenda($this->site_id);
		$agenda = $model->infoById ( $id );
		$compday = date('n月j日', strtotime($agenda['date']));
		if (!empty($gid)) {			
			try {				
				$groupdata_arr = empty($agenda['data']) ? array() : unserialize($agenda['data']);
				$groupdata = empty($groupdata_arr) ? array() : $groupdata_arr[$gid];	
				
			} catch ( Exception $e ) {
				$resp ['returnflag'] = 'ERROR';
				$resp ['message'] = $e->getMessage ();
					
				echo json_encode ( $resp );
			}
		}

		$teamodel = new CYdmatchbanner($this->site_id);
		$teamlist = $teamodel->listBy('`fish`="team"', 100, 0);
		$teamstr = '';
		if (!empty($teamlist)) {
			foreach ($teamlist as $team) {
				if (empty($groupdata['teams'])) {
					$teamstr .= '<div style="float:left;width:160px;border-right: 5px #cccccc solid;border-bottom: 5px #cccccc solid;"><input id="team_'.$team['id'].'" type="checkbox" name="team[]" value="'.$team['id'].'"/><label for="team_'.$team['id'].'"><img src="'.$team['img'].'" title="'.$team['title'].'" width="100px"/></label><span style="display:block">排名顺序：<input type="text" style="width:50px;" name="teamscore_'.$team['id'].'" /></span><span>是否晋级：<input type="radio" name="teamrise_'.$team['id'].'" value="0" id="teamriseno_'.$team['id'].'" checked="checked"/><label for="teamriseno_'.$team['id'].'">否</label><input type="radio" name="teamrise_'.$team['id'].'" value="1" id="teamriseyes_'.$team['id'].'"/><label for="teamriseyes_'.$team['id'].'">是</label></span></div>';
				} else {
					$team_score = array_search($team['id'], $groupdata['teams']);
					$riseteam_arr = (empty($groupdata['riseteam']) ? array() : $groupdata['riseteam']);
					$teamstr .= '<div style="float:left;width:160px;border-right: 5px #cccccc solid;border-bottom: 5px #cccccc solid;"><input id="team_'.$team['id'].'" type="checkbox" name="team[]" value="'.$team['id'].'" '.(in_array($team['id'], $groupdata['teams']) ? "checked='checked'" : "").'/><label for="team_'.$team['id'].'"><img src="'.$team['img'].'" title="'.$team['title'].'" width="100px"/></label><span style="display:block">排名顺序：<input type="text" style="width:50px;" name="teamscore_'.$team['id'].'" value="'.($team_score===false ? '' : $team_score).'" /></span><span>是否晋级：<input type="radio" name="teamrise_'.$team['id'].'" value="0" '.((in_array($team['id'], $riseteam_arr)) ? '' : 'checked="checked"').' id="teamriseno_'.$team['id'].'"/><label for="teamriseno_'.$team['id'].'">否</label><input type="radio" name="teamrise_'.$team['id'].'" value="1" '.((in_array($team['id'], $riseteam_arr)) ? 'checked="checked"' : '').' id="teamriseyes_'.$team['id'].'"/><label for="teamriseyes_'.$team['id'].'">是</label></span></div>';
				}
			}
		}
		
		$videostr = '';
		foreach (range(0, self::GROUP_VIDEO_NUM-1) as $value) {
			if (empty($groupdata['videos'])) {
				$videostr .= '视频名称：<input type="text" name="videoname'.$value.'" value=""/>视频地址：<input style="width:350px" type="text" name="videourl'.$value.'" value=""/><br>';
			} else {
				$videostr .= '视频名称：<input type="text" name="videoname'.$value.'" value="'.$groupdata['videos'][$value]['videoname'].'"/>视频地址：<input style="width:350px" type="text" name="videourl'.$value.'" value="'.$groupdata['videos'][$value]['videourl'].'"/><br>';
			}			
		}
		
		$isoverstr = '';
		$notoverstr = '';
		if ($groupdata['isover'] == 1) {
			$isoverstr = ' checked="checked" ';
		} else {
			$notoverstr = ' checked="checked" ';
		}
		
		$html = <<<EOT
<div class="groupForm">
	<form action="?" method="post" onsubmit="return Agenda.addGroup(this);">
		<input type="hidden" name="site_id" value="{$this->site_id}" />
		<input type="hidden" name="id" value="{$id}" />
		<input type="hidden" name="gid" value="{$gid}" />
		<table>
			<tr>
				<td class="ll" style="width: 80px;">分组名</td>
				<td class="rr" colspan="5">
					<input class="_datepicker" type="text" name="groupname" size="12" value="{$groupdata['groupname']}" style="width:200px" />
				</td>
			</tr>
			<tr>
				<td class="ll" style="width: 80px;">分组描述</td>
				<td class="rr" colspan="5">
				    <select name="compdate" data="{$groupdata['groupdesc']}" compday="{$compday}"></select>
					<!--input class="_datepicker" type="text" name="groupdesc" size="20" value="{$groupdata['groupdesc']}" style="width:200px" /-->
				</td>
			</tr>
			<tr>
				<td class="ll" style="width: 80px;">选择队伍</td>
				<td style="border-top: 5px #cccccc solid;border-left: 5px #cccccc solid;padding:0;width:660px">
					{$teamstr}
				</td>
			</tr>
			<tr>
				<td class="ll" style="width: 80px;">视频信息</td>
				<td>
					{$videostr}
				</td>
			</tr>
			<tr>
				<td class="ll" style="width: 80px;">是否结束</td>
				<td>
					<input type="radio" name="isover" value="0" {$notoverstr}/>否<input type="radio" name="isover" value="1" {$isoverstr}/>是		
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
	echo json_encode ( $resp );exit;

	}
	
	public function addGroup()
	{
		if (!Func::isPost()) {
			return false;
		}
		// ** 新闻对象
		$model = new CYdmatchagenda($this->site_id);
		// ** 数据
		$groupname = Func::p('groupname');
		$isover = intval(Func::p('isover'));
		list($groupdesc, $changci) = explode(',', Func::p('compdate'));
		
		$teams_init = Func::p('team');
		$teams = array();
		$riseteam = array();
		if (!empty($teams_init)) {
			foreach ($teams_init as $val) {
				$team_score = intval(Func::p('teamscore_'.$val));							
				if (!empty($team_score)) {
					$teams[$team_score] = $val;
				}
				$teamrise = intval(Func::p('teamrise_'.$val));
				if (!empty($teamrise)) {
					$riseteam[] = $val;
				}
			}
		}
		
		
		$videolist = array();
		foreach (range(0, self::GROUP_VIDEO_NUM) as $value) {
			$videoname = Func::p('videoname'.$value);			
			$videourl = Func::p('videourl'.$value);
			if (!empty($videoname) && !empty($videourl)) {
				array_push($videolist, array('videoname'=>$videoname, 'videourl'=>$videourl));
			}
			unset($videoname, $videourl);
		}
		
		
		
		// ** 空ID新增
		$id = Func::p('id');
		$gid = Func::p('gid');
		
		if (empty($id)) {
			throw new Exception('数据异常');
		}
		
		$agenda = $model->infoById ( $id );
		$group = array('groupname'=>$groupname, 'teams'=>$teams, 'videos'=>$videolist, 'groupdesc'=>$groupdesc, 'changci'=>$changci, 'isover'=>$isover, 'riseteam'=>$riseteam);
		
		if (empty($gid)) {			
			if (empty($agenda['data'])) {
				$data['data'] = serialize(array('1'=>$group));
			} else {
				$data_arr = unserialize($agenda['data']);
				$data_arr[count($data_arr)+1] = $group;
				$data['data'] = serialize($data_arr);
			}			
		} else {
			$data_arr = unserialize($agenda['data']);
			$data_arr[$gid] = $group;
			$data['data'] = serialize($data_arr);
		}
		
		// ** 保存
		$succ = $model->updateBy($data, '`id`='.$id);
		
		$this->clearCache();
		
		// ** 若保存失败
		if (!$succ) {
			$resp['returnflag'] = 'DB_ERROR';
			echo json_encode($resp);
			return false;
		}
		
		$resp['returnflag'] = 'OK';
		echo json_encode($resp);
		return true;
	}

	
	private function clearCache()
	{
		$csite = new CSite();
		$site = $csite->infoById($this->site_id);
		$mc = Mc::getInstance(array('host'=>$site['mmhost'], 'port'=>$site['mmport']));		
		$mc->del(self::MEMCACHE_AGENDA_KEY);				
	}
}
?>