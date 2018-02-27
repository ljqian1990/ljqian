<?php
namespace Jigsaw\Controls;

use Jigsaw\Models\Project as ProjectModel;
use Jigsaw\Models\Gametype as GametypeModel;
use Jigsaw\Models\Channel as ChannelModel;

class Project extends Base
{

    const CACHE_BATCHSYNC_LOCK = 'clp:control:project:batchsynclock:';
    const CACHE_BATCHSYNC_LOCK_TIME = 180;

    private $projectmodel = null;

    private $gametypemodel = null;

    private $channelmodel = null;

    public function __construct()
    {
        $this->projectmodel = ProjectModel::getInstance();
        $this->gametypemodel = GametypeModel::getInstance();
        $this->channelmodel = ChannelModel::getInstance();
        parent::__construct();
    }

    /**
     * 保存项目信息接口
     *
     * @return boolean
     */
    public function save()
    {
        $title = $this->request->getRequest('title', 'string', false);
        $keyword = $this->request->getRequest('keyword', 'string', false);
        $desc = $this->request->getRequest('desc', 'string');
        $csslink = $this->request->getRequest('csslink', 'string', false);
        $jslink = $this->request->getRequest('jslink', 'string', false);
        $html = $this->request->getRequest('html', 'string', false);
        $conf = $this->request->getRequest('conf', 'string', false);
        $channelid = $this->request->getRequest('channelid', 'int', false);
        $fulllink = $this->request->getRequest('fulllink', 'string', true);
        $bgcolor = $this->request->getRequest('bgcolor', 'string', true);
        $id = $this->request->getRequest('id', 'int', true);
        
        $project = [
            'title' => $title,
            'keyword' => $keyword,
            'desc' => $desc,
            'csslink' => $csslink,
            'jslink' => $jslink,
            'html' => $html,
            'conf' => $conf,
            'channelid' => $channelid,
            'fulllink' => $fulllink,
            'bgcolor' => $bgcolor
        ];
        
        if (empty($id)) {
            $proid = $this->projectmodel->addProject($project);
            
            $htmlfile = $this->saveHtml($proid);
            
            $project = [
                'htmlfile' => $htmlfile
            ];
            
            $this->projectmodel->editProject($proid, $project);
        } else {
            $proid = $id;
            
            $this->projectmodel->editProject($proid, $project);
            $htmlfile = $this->saveHtml($proid);
        }
        
        //$this->Sync($htmlfile);
        
        if (ISDEBUG) {
            return $this->config->constant('htmlurl') . $htmlfile;
        } else {
            $gametypeinfo = $this->env->get($this->config->constant('jigsaw_cur_gametype_info'), false);
            return trim($gametypeinfo['siteurl'], '/') .'/'. trim($htmlfile, '/');
        }
    }

    /**
     * 移除项目接口
     *
     * @return boolean
     */
    public function remove()
    {
        $proid = $this->request->getRequest('id', 'int', false);
        
        $this->projectmodel->editProject($proid, ['html'=>'']);
        $this->saveHtml($proid);
        
        $this->projectmodel->delProject($proid);
        
        //$this->Sync($htmlfile);
        
        return true;
    }

    /**
     * 获取项目列表接口
     *
     * @return unknown
     */
    public function getList()
    {
        $page = $this->request->getRequest('page', 'int', true, 1);
        $page = $page <= 1 ? 1 : $page;
        $size = $this->config->constant('projectlist');
        $offset = ($page - 1) * $size;
        
        $channelid = $this->request->getRequest('channelid', 'int', true);
        $total = $this->projectmodel->getCount($channelid);
        $totalpage = ceil($total / $size);
        $list = $this->projectmodel->getProjects($offset, $size, $channelid);
        
        $gametypelist = $this->gametypemodel->getGametypes();
        $gametypelist = $this->func->setFieldIndex($gametypelist, 'id');
        $channellist = $this->channelmodel->getChannels();
        $channellist = $this->func->setFieldIndex($channellist, 'id');
        
        $gametypeinfo = $this->env->get($this->config->constant('jigsaw_cur_gametype_info'), false);
        
        $list = array_map(function ($val) use($gametypelist, $channellist, $gametypeinfo) {
            if (ISDEBUG) {
                $val['htmlfile'] = $this->config->constant('htmlurl') . $val['htmlfile'];
            } else {
                $val['htmlfile'] = trim($gametypeinfo['siteurl'], '/') .'/'. trim($val['htmlfile'], '/');
            }
            
            $val['channelname'] = $channellist[$val['channelid']]['name'];
            $val['gametypename'] = $gametypelist[$val['gametype']]['name'];
            return $val;
        }, $list);
        
        return [
            'total' => $totalpage,
            'list' => $list
        ];
    }
    
    public function exportList()
    {
        $list = $this->projectmodel->getProjects(0, 999999);
        
        $gametypelist = $this->gametypemodel->getGametypes();
        $gametypelist = $this->func->setFieldIndex($gametypelist, 'id');
        $channellist = $this->channelmodel->getChannels();
        $channellist = $this->func->setFieldIndex($channellist, 'id');
        
        $gametypeinfo = $this->env->get($this->config->constant('jigsaw_cur_gametype_info'), false);
        
        $data = "标题,所属渠道,所属分类,创建人,预览地址\r\n";
		if (!empty($list)) {
			foreach ($list as $value) {
                if (ISDEBUG) {
                    $htmlfile = $this->config->constant('htmlurl') . $value['htmlfile'];
                } else {                    
                    $htmlfile = trim($gametypeinfo['siteurl'], '/') .'/'. trim($value['htmlfile'], '/');
                }
				$data .= $value['title'].','.$channellist[$value['channelid']]['name'].','.$gametypelist[$value['gametype']]['name'].','.$value['author'].','.$htmlfile."\r\n";
			}
		}
		$this->_exportCSV($gametypeinfo['name'].'广告落地页列表.csv', $data);        
    }
    
    private function _exportCSV($filename, $data)
    {
		// 		$data = iconv('utf-8','gbk', $data);
		$data = "\xEF\xBB\xBF".$data;
	
		header("Content-type:text/csv;charset=utf-8");
		header("Content-Disposition:attachment;filename=".$filename.'.csv');
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');
		echo $data;exit;
	}

    /**
     * 获取项目详细接口
     */
    public function getInfo()
    {
        $proid = $this->request->getRequest('id', 'int', false);
        $info = $this->projectmodel->getInfo($proid);
        
        if (ISDEBUG) {
            $info['htmlfile'] = $this->config->constant('htmlurl') . $info['htmlfile'];
        } else {
            $gametypeinfo = $this->env->get($this->config->constant('jigsaw_cur_gametype_info'), false);
            $info['htmlfile'] = trim($gametypeinfo['siteurl'], '/') .'/'. trim($info['htmlfile'], '/');
        }
        
        return $info;
    }

    /**
     * 上传图片接口
     *
     * @return string
     */
    public function uploadFile()
    {
        $file = $_FILES['img'];
        $url = $this->func->uploadImg($file);
        return $url;
    }

    public function uploadFile_v2()
    {
        $file = $_FILES['img'];
        $backurl = $this->request->getRequest('backurl', 'string', false);
        $backurl = urldecode($backurl);
        $url = $this->func->uploadImg($file);
        header('Location: ' . $backurl . '&success=' . urlencode($url));
        // return $url;
    }
    
    public function batchSave()
    {
        $list = $this->projectmodel->getProjects(0, 999999);
        if (!empty($list)) {
            foreach ($list as $info) {
                $this->saveHtml($info['id']);
            }
        }
        echo 'OK';exit;
    }

    public function getComponents()
    {}

    /**
     * 保存html信息到文件中，将文件推送到前台服务器，并调用cdn推送接口
     *
     * @param unknown $id            
     * @param unknown $html            
     */
    private function saveHtml($id)
    {
        $path = $this->makeHtml($id);
        return $path;
    }

    private function Sync($path)
    {
        if (! ISDEBUG) {
            $gametypeinfo = $this->env->get($this->config->constant('jigsaw_cur_gametype_info'), false);
            $fullHtmlUrl = trim($gametypeinfo['siteurl'], '/') .'/'. trim($path, '/');
            
            $this->fileSync();
            $this->cdnSync($fullHtmlUrl);
        }
        return true;
    }

    /**
     * 保存html信息到文件中，并根据id生成文件名
     *
     * @param unknown $id            
     * @param unknown $html            
     * @return string
     */
    private function makeHtml($id)
    {
        $filename = $this->func->fname($id);
        
        $info = $this->projectmodel->getInfo($id);
        if (empty($info)) {
            $this->exception->throwSystemException($this->config->error('OBJECT_EMPTY'));
        }
        $date = date('Y-m-d', strtotime($info['datetime']));
        
        $gametypeobj = $this->gametypemodel->getGametypeById($info['gametype']);
        if (ISDEBUG) {
            $htmlpath = $this->config->constant('htmlpath') . $gametypeobj['sitepath'] . '/' . $date . '/';
        } else {
            $htmlpath = $this->config->constant('ucms_sites_dir') . $gametypeobj['sitepath'] . '/site/html/clp/' . $date . '/';
        }
        
        if (! is_dir($htmlpath)) {
            mkdir($htmlpath, 0755, true);
        }
        
        $htmlfile = $htmlpath . $filename;
        
        $html = str_replace(' ', '+', $info['html']);
        $html = base64_decode($html);
        
        $cssstr = '';
        $info['csslink'] = "//cdnsapi.ztgame.com/site/clp/v1/css/theme1.css";
        if ($info['csslink']) {
            $cssarr = explode(',', $info['csslink']);
            $cssstrarr = array_map(function ($val) {
                return '<link rel="stylesheet" href="' . $val .'?'.time(). '">';
            }, $cssarr);
            $cssstr = implode('', $cssstrarr);
        }
        $jsstr = '';
        $info['jslink'] = "//cdnsapi.ztgame.com/site/clp/v1/js/vendors.js,//cdnsapi.ztgame.com/site/clp/v1/js/theme1.js";
        if ($info['jslink']) {
            $jsarr = explode(',', $info['jslink']);
            $jsstrarr = array_map(function ($val) {
                return '<script type="text/javascript" src="' . $val . '?'.time().'"></script>';
            }, $jsarr);
            $jsstr = implode('', $jsstrarr);
        }
        
        $wx_share_appid = $this->config->constant('wx_share_appid');
        
        $bgcolorstr = empty($info['bgcolor']) ? '' : "<style>body{background-color: #{$info['bgcolor']}}</style>";
        
        $htmlstr = <<<EOT
<!DOCTYPE html>
<html lang="en">
<head>
	<title>{$info['title']}</title>
	<meta charset="UTF-8">
	<meta name="keywords" content="{$info['keyword']}">
	<meta name="description" content="{$info['desc']}">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, maximum-scale=1">
	{$cssstr}
	{$bgcolorstr}
</head>
<body>
{$html}
{$jsstr}
<script type="text/javascript">
        var _gadate = new Date().getTime();
        var _maq = _maq || [];
        var _gatype  = {$info['gametype']};    //游戏类型
        _maq.push(['_setAccount', _gatype]);
 
    (function() {
        var ma = document.createElement('script'); ma.type = 'text/javascript'; ma.async = true;
        ma.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'gaanalytics.ztgame.com/analytics.js?'+_gadate;
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ma, s);
    })(); 
</script> 
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?{$gametypeobj['baiduhash']}";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
</body>
</html>     
EOT;
        
        file_put_contents($htmlfile, $htmlstr);
        if (ISDEBUG) {
            return $gametypeobj['sitepath'] . '/' . $date . '/' . $filename;
        } else {
            return '/html/clp/' . $date . '/' . $filename;
        }
    }

    private function fileSync()
    {
        if (! $this->loadService('filesync')->Sync()) {
            $this->exception->throwSystemException($this->config->error('FILE_SYNC_ERROR'));
        }
        return true;
    }

    private function cdnSync($fileurl, $type='')
    {
        if (empty($type)) {
            $type = $this->loadService('cdnsync')::FILE_TYPE;
        }
        $starttime = time();
        $ret = $this->loadService('cdnsync')->Sync($fileurl, $type);
        $endtime = time();
        if ($ret['ret'] != 1) {
            $url = $this->config->constant('cdn_sapi_url');
            $this->exception->throwSystemException($this->config->error('CDN_SYNC_ERROR') . ':' . $ret['msg'] . ';file:'.$fileurl.';cdn_sapi_url:'.$url.';use_time:'.($endtime-$starttime));
        }
        return true;
    }
    
    public function batchSync()
    {
        $username = $this->func->getCurrentUser();
        $gametype = $this->env->get('gametype');
        $cachekey = self::CACHE_BATCHSYNC_LOCK . $gametype . ':' . $username;
        if ($this->cache->get($cachekey)) {
            $this->exception->throwUserException($this->config->error('CDN_SYNC_OFTEN'));
        }
        
        $this->fileSync();
        
        $gametypeinfo = $this->env->get($this->config->constant('jigsaw_cur_gametype_info'), false);
        $siteurl = trim($gametypeinfo['siteurl'], '/') . '/html/clp/';
        $this->cdnSync($siteurl, $this->loadService('cdnsync')::DIR_TYPE);
        
        $this->cache->set($cachekey, 1, self::CACHE_BATCHSYNC_LOCK_TIME);
        
        return true;
    }
}