<?php

class PlgDemoBanner implements IPlugin
{

    const MEMCACHE_BANNER_KEY = 'balls_demo_banner_';

    private $catinfo = array(
        'team' => array(
            'name' => '参赛战队'
        ),
        'jieshuo' => array(
            'name' => '游戏解说'
        ),
        'meiti' => array(
            'name' => '合作媒体'
        ),
        'matchimg' => array(
            'name' => '比赛赛程示意图'
        )
    );

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
        $model = new CDemobanner($this->site_id);
        $where = "1=1 order by `sort` desc, `id` desc";
        $total = $model->countBy($where);
        
        $this->view['curr_site_id'] = $this->site_id;
        $this->view['model'] = $_GET['model'];
        $this->view['catinfo'] = $this->catinfo;
        $this->view['list'] = $model->listBy($where);
    }

    /**
     * 编辑banner
     */
    public function edit()
    {
        $id = Func::p('id');
        
        $model = new CDemobanner($this->site_id);
        
        if (! empty($id)) {
            $model->load("`id`='{$id}'");
        } else {
            $max_sort_data = $model->infoBy('`fish`="' . Func::p('fish') . '" order by `sort` desc');
            $model->sort = intval($max_sort_data['sort']) + 1;
            $model->stat = 1;
        }
        
        $model->fish = Func::p('fish');
        $model->title = Func::p('title');
        $model->img = Func::p('img');
        $model->link = Func::p('link');
        $model->memo = Func::p('memo', true);
        
        $model->time = Func::p('time') ? Func::p('time') : date("Y-m-d H:i:s");
        
        $resp['returnflag'] = 'OK';
        
        try {
            $model->save();
            if ($model->fish == 'news') {
                $this->create_thumb_image($model->img, 'picture');
            }
            
            $resp['flag'] = $model->fish;
            $resp['id'] = $model->id;
            $resp['title'] = $model->title;
            $resp['img'] = $model->img;
            $resp['link'] = $model->link;
            $resp['fish'] = $model->fish;
            $resp['time'] = date("Y-m-d", strtotime($model->time));
            $resp['memo'] = $model->memo;
            $resp['stat'] = $model->stat;
        } catch (Exception $e) {
            $resp['returnflag'] = 'ERROR';
            $resp['message'] = $e->getMessage();
        }
        
        $this->clearCache($model->fish);
        echo json_encode($resp);
    }

    /**
     * 更改banner的显示状态
     */
    public function stat()
    {
        $id = Func::p('id');
        
        $model = new CDemobanner($this->site_id);
        $ret = $model->infoById($id);
        $stat = ($ret['stat'] == 1) ? 0 : 1;
        
        $model->load("`id`='{$id}'");
        $model->stat = $stat;
        
        $resp['returnflag'] = 'OK';
        $resp['flag'] = $stat;
        try {
            $model->save();
            
            $this->clearCache($model->fish);
        } catch (Exception $e) {
            $resp['returnflag'] = 'ERROR';
            $resp['message'] = $e->getMessage();
        }
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
            $model = new CDemobanner($this->site_id);
            $ret = $model->infoById($id);
            try {
                $model->deleteById($id);
                
                $this->clearCache($ret['fish']);
                
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
        $sort_id = Func::p('sort_id');
        if (empty($sort_id)) {
            $resp['returnflag'] = 'NO SORT ID';
            $resp['message'] = 'no sortid assign';
        } else {
            $model = new CDemobanner($this->site_id);
            try {
                $length = count($sort_id);
                foreach ($sort_id as $key => $id) {
                    
                    $model->updateBy(array(
                        'id' => $id,
                        'sort' => (int) ($length - $key)
                    ));
                }
                
                $ret = $model->infoById($sort_id[0]);
                $this->clearCache($ret['fish']);
                
                $resp['returnflag'] = 'OK';
            } catch (Exception $e) {
                $resp['returnflag'] = 'ERROR';
                $resp['message'] = $e->getMessage();
            }
        }
        echo json_encode($resp);
    }

    private function clearCache($cat)
    {
        $csite = new CSite();
        $site = $csite->infoById($this->site_id);
        $mc = Mc::getInstance(array(
            'host' => $site['mmhost'],
            'port' => $site['mmport']
        ));
        if (! empty($cat)) {
            $mc->del(self::MEMCACHE_BANNER_KEY . $cat);
        }
    }

    /*
     * 创建缩略图
     * $original_img @parm 原图路径
     * $type @parm 创建缩略图的类型，例：壁纸下载，视频查看，投稿图片
     */
    private function create_thumb_image($original_img, $type)
    {
        // 系统生成缩略图
        $oThumb = new ZtThumbnail();
        $thumb_img = $oThumb->create($original_img, $type);
        return $thumb_img;
    }
}
?>