<?php
class PlgYdmatchMedianews implements IPlugin {
	const MEMCACHE_BANNER_KEY = 'balls_ydmatch_banner_newslist';	
	
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
		$keyw = Func::g('keyword');
		$where = '1';
		if (!empty($keyw)) {
			$where .= " and `title` like '%{$keyw}%' ";
		}
		
		$model = new CYdmatchbanner ( $this->site_id );
		$where .= " and `fish`='newslist' order by `sort` desc, `id` desc";
		$total = $model->countBy ( $where );
		$size = 15;
		$page = new Page($total, $size);
		
		$list = $model->listBy($where, $size, $page->offset());
		
		$this->view['curr_site_id'] = $this->site_id;
		$this->view['model'] = $_GET['model'];
		$this->view['catinfo'] = $this->catinfo;
		$this->view ['list'] = Func::xnop($list, $size);
		$this->view['navpage'] = $page->make(null, 5, 'bluepage');
	}
	
	/**
	 * 编辑banner
	 */
	public function edit() {
		$id = Func::p ( 'id' );
		
		$model = new CYdmatchbanner ( $this->site_id );
		
		if (! empty ( $id )) {
			$model->load ( "`id`='{$id}'" );
		} else {
			$max_sort_data = $model->infoBy('`fish`="newslist" order by `sort` desc');
			$model->sort = intval($max_sort_data['sort']) +1;
			$model->stat = 1;
		}		
		$model->fish = 'newslist';
		$model->title = Func::p ( 'title' );		
		$model->link = Func::p ( 'link' );				
		$model->time = Func::p ( 'time' ) ? Func::p ( 'time' ) : date ( "Y-m-d H:i:s" );
		
		$resp ['returnflag'] = 'OK';
		
		try {
			$model->save ();
			
			$resp ['flag'] = $model->fish;
			$resp ['id'] = $model->id;
			$resp ['title'] = $model->title;		
			$resp ['link'] = $model->link;
			$resp ['fish'] = $model->fish;
			$resp ['time'] = date ( "Y-m-d", strtotime ( $model->time ) );		
			$resp ['stat'] = $model->stat;
		} catch ( Exception $e ) {
			$resp ['returnflag'] = 'ERROR';
			$resp ['message'] = $e->getMessage ();
		}
		
		$this->clearCache();
		echo json_encode ( $resp );
	}
	
	/**
	 * 更改banner的显示状态
	 */
	public function active() {
		$id = Func::p ( 'id' );
		
		$model = new CYdmatchbanner ( $this->site_id );
		$ret = $model->infoById ( $id );
		$stat = ($ret ['stat'] == 1) ? 0 : 1;
		
		$model->load ( "`id`='{$id}'" );
		$model->stat = $stat;
		
		$resp ['returnflag'] = 'OK';
		$resp ['flag'] = $stat;
		try {
			$model->save ();
			
			$this->clearCache();
		} catch ( Exception $e ) {
			$resp ['returnflag'] = 'ERROR';
			$resp ['message'] = $e->getMessage ();
		}
		echo json_encode ( $resp );
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
			$model = new CYdmatchbanner ( $this->site_id );
			try {
				$model->deleteByIDs ( $id );		
				$this->clearCache();
				$resp ['returnflag'] = 'OK';
			} catch ( Exception $e ) {
				$resp ['returnflag'] = 'DB_ERRER';
				$resp ['message'] = $e->getMessage ();
			}
		}
		
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
		
		$model = new CYdmatchbanner ( $this->site_id );
		try {
			$model->updateBy ( array (
					'id' => $oid,
					'sort' => $nsort 
			) );
			$model->updateBy ( array (
					'id' => $nid,
					'sort' => $osort 
			) );
			$this->clearCache();
			$resp ['returnflag'] = 'OK';
		} catch ( Exception $e ) {
			$resp ['returnflag'] = 'DB_ERRER';
			$resp ['message'] = $e->getMessage ();
		}
		
		echo json_encode ( $resp );
	}
	
	public function form()
	{
		$id = Func::p('id');
		if (!empty($id)) {
			$model = new CYdmatchbanner($this->site_id);
			try {
				$this->_news = $model->infoById ( $id );
			} catch ( Exception $e ) {
				$resp ['returnflag'] = 'ERROR';
				$resp ['message'] = $e->getMessage ();
			
				echo json_encode ( $resp );
			}
		}
		
		$html = <<<EOT
<div class="infoForm">
	<form action="?" method="post" onsubmit="return Medianews.save(this);">
		<input type="hidden" name="site_id" value="{$this->site_id}" />
		<input type="hidden" name="id" value="{$this->_v('id')}" />
		<table>
			<tr>
				<td class="ll" style="width: 80px;">标题</td>
				<td class="rr" colspan="5">
					<input type="text" name="title" value="{$this->_v('title')}" size="75" />
				</td>
			</tr>
			<tr>
				<td class="ll" style="width: 80px;">链接地址</td>
				<td class="rr" colspan="5">
					<input type="text" name="link" value="{$this->_v('link')}" size="75" />
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
		return empty ( $this->_news ) ? '' : (isset ( $this->_news[$key] ) ? $this->_news[$key] : '');
	}
	
	
	private function clearCache()
	{
		$csite = new CSite();
		$site = $csite->infoById($this->site_id);
		$mc = Mc::getInstance(array('host'=>$site['mmhost'], 'port'=>$site['mmport']));		
		$mc->del(self::MEMCACHE_BANNER_KEY);
		
	}
}
?>