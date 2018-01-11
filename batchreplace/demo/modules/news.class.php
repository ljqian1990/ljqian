<?php
class PlgDemoNews implements IPlugin {
	const MEMCACHE_NEWS_COMP_LIST_KEY = 'balls_demo_news_comp_list';
	const MEMCACHE_NEWS_NEWS_LIST_KEY = 'balls_demo_news_news_list';
	const MEMCACHE_NEWS_INFO_KEY = 'balls_demo_newsinfo_';
	
	private $_news;
	
	private $catinfo = array('comp'=>array('name'=>'赛事'), 'news'=>array('name'=>'新闻'));	
	
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
	
	/**
	 * 显示新闻列表
	 */	
	public function index()
	{			
		// ** 新闻分类信息
		$catinfo = $this->catinfo;
	
		$catopt = array();
	
		foreach ($catinfo as $key => $name) {
			$catopt[$key] = $name['name'];
		}
	
		// ** 取新闻
	
		$size = 15;
	
		$news = new CDemonews($this->site_id);
		
		$type = Func::g('type');
		$keyw = Func::g('keyword');
		$from = Func::g('from');
		$to   = Func::g('to');
	
		$where = "1";
	
		if (!empty($type)) {
			$where .= " and `type`='{$type}' ";
		}
	
		if (!empty($keyw)) {
			$where .= " and (`title` like '%{$keyw}%' or content like '%{$keyw}%')";
		}
	
		if (!empty($from)) {
			$where .= " and `publishdate`>='{$from}' ";
		}
	
		if (!empty($to)) {
			$where .= " and `publishdate`<='{$to}' ";
		}
	
		$total = $news->countBy($where);
	
		$page = new Page($total, $size);
	
		$where .= " order by `sort` desc,`id` desc";
	
		$list = $news->listBy($where, $size, $page->offset());		
	
		$smarty = SmartyEx::init();
		$smarty->assign("list", Func::xnop($list, $size));
		$smarty->assign('cat', $catopt);
		$smarty->assign('model', $_GET['model']);
		$smarty->assign('navpage', $page->make(null, 5, 'bluepage'));
		$smarty->assign('navGameName', $this->site_name);
		$smarty->assign('curr_site_id', $this->site_id);
	}
	
	/**
	 * 编辑新闻内容
	 */
	public function edit() {
		$id   = Func::p('id');
		if (empty($id)){
			$this->save('insertBy');
		}else{
			$this->save('updateBy');
		}		
	}
	
	/**
	 * 保存新闻方法实现
	 * 保存新闻方法实现
	 * @param string 执行方法
	 * @return bool
	 */
	private function save($exec)
	{
		if (!Func::isPost()) {
			return false;
		}
		// ** 新闻对象
		$news = new CDemonews($this->site_id);
		// ** 数据
		$data['type']   = Func::p('type');
		$data['title']  = Func::p('title');
		$data['islink'] = Func::p('islink') == 1 ? 1 : 0;
		if ($data['islink'] == 1) {
			$data['content'] = Func::p('linkaddr');
		} else {
			$data['content'] = Func::p('content', true);
		}
		###########
		$data['author'] = Func::p ( 'author' );			
		 
		#################################
		$data['active']          = Func::p('active');
		$data['publishdate']     = Func::p('publishdate');
		if (empty($data['publishdate'])){
			$data['publishdate'] = date('Y-m-d');
		}
		$data['seo_keyword']     = Func::p('seo_keyword');
		$data['seo_description'] = Func::p('seo_description');
		
	
		//新闻封面图片,考虑到有些官网新闻表并没有此字段，所以先判断 2013-06-18
		if (Func::p('img')) {
			$data['img'] = Func::p('img');
		}
	
		// ** 定时发布信息
		$timerflag = Func::p('timerflag');
		if ($timerflag) {
			$data['pub_timer'] = Func::p('pub_timer_date');
			$data['pub_timer'] .= ' ';
			$data['pub_timer'] .= Func::p('pub_timer_hour');
			$data['pub_timer'] .= ':';
			$data['pub_timer'] .= Func::p('pub_timer_minute');
			$data['pub_timer'] .= ':00';
		} else {
			$data['pub_timer'] .= '';
		}
		###########################
		// ** 空ID新增
		$id   = Func::p('id');
		$time = time();
		if (empty($id)) {
			$data['submitdate'] = $data['lasttime'] = date('Y-m-d H:i:s', $time);
			//添加排序
			$max_sort_data = $news->infoBy ( '1 order by sort desc' );
			$data['sort'] = ( int ) $max_sort_data ['sort'] + 1;
		} else {
			$data['id']       = $id;
			$data['lasttime'] = date('Y-m-d H:i:s', $time);
		}
		
		// ** 保存
		$succ = $news->$exec($data);
		
		$tmpid = empty($id) ? $succ : $id;
		$this->clearCache($tmpid);
		
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
	 * 更改新闻显示状态
	 */
	public function active() {
		$id = Func::p ( 'id' );
		
		$model = new CDemonews ( $this->site_id );
		$ret = $model->infoById ( $id );
		$active = ($ret ['active'] == 1) ? 0 : 1;
		
		$model->load ( "`id`='{$id}'" );
		$model->active = $active;
		
		$resp ['returnflag'] = 'OK';
		$resp ['flag'] = $active;
		try {
			$model->save ();
		} catch ( Exception $e ) {
			$resp ['returnflag'] = 'ERROR';
			$resp ['message'] = $e->getMessage ();
		}		

		$this->clearCache($id);
		echo json_encode ( $resp );
	}
	
	/**
	 * 删除新闻信息
	 */
	public function del() {
		$id = Func::p ( 'del_id' );
		if (empty ( $id )) {
			$resp ['returnflag'] = 'NO_ID';
			$resp ['message'] = 'no id assign';
		} else {
			$model = new CDemonews ( $this->site_id );
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
	 * 更改新闻排序
	 */
	public function zort() {
		$oid = Func::p ( 'oid' );
		$osort = Func::p ( 'osort' );
		$nid = Func::p ( 'nid' );
		$nsort = Func::p ( 'nsort' );
		
		$model = new CDemonews ( $this->site_id );
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
	 */
	public function form() {
		$id = Func::p ( 'id' );
		if (! empty ( $id )) {
			$model = new CDemonews ( $this->site_id );
			try {
				$this->_news = $model->infoById ( $id );
			} catch ( Exception $e ) {
				$resp ['returnflag'] = 'ERROR';
				$resp ['message'] = $e->getMessage ();
				
				echo json_encode ( $resp );
			}
		}
		
		//分类
		$catinfo = $this->catinfo;
		$options = '';
		foreach ($catinfo as $key=>$cat) {
			$options .= '<option label="'.$cat['name'].'" value="'.$key.'" '.(($this->_v('type') == $key)?'selected="selected"':'').'>'.$cat['name'].'</option>';
		}
		
		$resp ['html'] = '
 <div class="infoForm">
 <form action="?" method="post" onsubmit="return News.save(this);">
 <input type="hidden" name="site_id" value="' . $this->site_id . '" />
 <input type="hidden" name="id" value="' . $this->_v ( 'id' ) . '" />
 <table>
 <tr>
 <td class="ll" style="width: 80px;">新闻标题</td>
 <td class="rr" colspan="5">
 <input type="text" name="title" value="' . $this->_v ( 'title' ) . '" size="75" />
 </td>
 <td class="ll" style="width: 80px;">发布时间</td>
 <td class="rr">
 <input type="text" name="publishdate" class="_datepicker" size="12" readonly value="' . $this->_v ( 'publishdate' ) . '" />
 </td>
 </tr>
 <tr>
 <td class="ll">所属类型</td>
 <td class="rr" colspan="2" id="atypetd">
 <select name="type" id="articletype" onchange="News.typechange()">
 <option value="">--全部--</option>
 '.$options.'
 </select>
 </td>
 <td class="ll" style="width: 80px;">是否显示</td>
 <td class="rr">
 <input type="checkbox" value="1" name="active" ' . (($this->_v ( 'active' ) == 0) ? '' : 'checked') . ' />
 </td>
 <td class="ll" style="width: 80px;">作者</td>
 <td class="rr">
 <input type="text" value="' . $this->_v ( 'author' ) . '" name="author" />
 </td>

 </tr>
 
 <tr>
 <td class="ll" style="width: 80px;">关键字</td>
 <td class="rr" style="padding-right: 0px;">
 <input type="text" name="seo_keyword" value="' . $this->_v ( 'seo_keyword' ) . '" style="margin-right: 0; width: 180px;" />
 </td>
 <td class="ll" style="width: 80px;">简短描述</td>
 <td class="rr" class="rr" colspan="5" style="padding-right: 0px;">
 <input type="text" name="seo_description" value="' . $this->_v ( 'seo_description' ) . '" style="margin-right: 0; width: 400px;" />
 </td>
 </tr>
 <tr id="_imgbrowser" class="ui-helper-hidden">
 <td class="ll" style="width: 80px;">封面预览</td>
 <td class="rr" colspan="7">
 <div style="width: 278px; height: 158px;">
 <img src="' . $this->_v ( 'img' ) . '" style="max-width: 278px; max-height: 158px; border: 0;" />
 </div>
 </td>
 </tr>
 <tr>
 <td class="ll" style="width: 80px;">封面图片</td>
 <td class="rr" colspan="7">
 <input id="_uploadimg" type="text" name="img" value="' . $this->_v ( 'img' ) . '" size="28" readonly />
 <button type="button" onmouseover="$.browpop(event,this,imgcoreback)" icon="folder-open" class="ui-button" title="浏览">浏览</button>
 <span style="color:red">*此功能无法自动生成缩略图，上传前请自行裁剪</span>
 </td>
 </tr>
 <tr>
 <td class="ll">设为链接</td>
 <td colspan="7" class="rr">
 <input type="checkbox" name="islink" value="1" ' . (($this->_v ( 'islink' ) == 0) ? '' : 'checked') . ' onclick="News.chglink(this)" />
 <span id="linkPanel" class="ui-helper-hidden" ' . (($this->_v ( 'islink' ) == 0) ? '' : ('style="display:inline;"')) . '>
 &nbsp;&nbsp;链接地址:
 <input type="text" name="linkaddr" value="' . (($this->_v ( 'islink' ) == 0) ? '' : $this->_v ( 'content' )) . '" style="width: 635px;" />
 </span>
 </td>
 </tr>
 <tr id="txtPanel" '.(($this->_v ( 'islink' ) == 0) ? '' : 'style="display:none"').'>
 <td colspan="8">
 <textarea id="_editor" name="content">' . $this->_v ( 'content' ) . '
 </textarea>
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

 if($this->_v('type')=='gamedata'){
 	$resp['html'] .= '<script>News.typechange();</script>';
 }
		$resp ['returnflag'] = 'OK';
		
		echo json_encode ( $resp );exit;
	}
	
	/**
	 * 获取单条新闻的指定key的value值
	 * 
	 * @param unknown $key        	
	 */
	private function _v($key) {
		return empty ( $this->_news ) ? '' : (isset ( $this->_news [$key] ) ? $this->_news [$key] : '');
	}
	
	private function clearCache($id='')
	{
		$csite = new CSite();
		$site = $csite->infoById($this->site_id);
		$mc = Mc::getInstance(array('host'=>$site['mmhost'], 'port'=>$site['mmport']));
		$mc->del(self::MEMCACHE_NEWS_COMP_LIST_KEY);
		$mc->del(self::MEMCACHE_NEWS_NEWS_LIST_KEY);		
		
		if (!empty($id)) {
			$mc->del(self::MEMCACHE_NEWS_INFO_KEY.$id);
		}
	}
}

?>