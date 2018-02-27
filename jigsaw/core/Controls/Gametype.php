<?php
namespace Jigsaw\Controls;

use Jigsaw\Models\Gametype as GametypeModel;
use Jigsaw\Models\User2site as User2siteModel;

class Gametype extends Base
{

    private $gametypeModel = null;

    public function __construct()
    {
        $this->gametypeModel = GametypeModel::getInstance();
        $this->user2siteModel = User2siteModel::getInstance();
        parent::__construct();
    }

    public function save()
    {
        $id = $this->request->getRequest('id', 'int', false);
        $name = $this->request->getRequest('name', 'string', false);
        $sitepath = $this->request->getRequest('sitepath', 'string', false);
        $baiduhash = $this->request->getRequest('baiduhash', 'string', true);
        $siteurl = $this->request->getRequest('siteurl', 'string', false);
        
        $gametype = [
            'id' => $id,
            'name' => $name,
            'sitepath' => $sitepath,
            'baiduhash' => $baiduhash,
            'siteurl' => $siteurl
        ];
        
        if ($this->gametypeModel->getGametypeById($id)) {
            $this->gametypeModel->editGametype($gametype, $id);
        } else {
            $this->gametypeModel->addGametype($gametype);
        }
        
        return true;
    }
    
    public function remove()
    {
        $id = $this->request->getRequest('id', 'int', false);
        $this->gametypeModel->delGametype($id);
        $this->user2siteModel->delByGametypeid($id);
        return true;
    }
    
    /**
     *
     * @deprecated
     *
     */
    public function addGametype()
    {
        $str = file_get_contents(dirname(__FILE__) . '/data');
        $arr = explode("\r\n", $str);
        foreach ($arr as $val) {
            list ($id, $name) = explode(',', $val);
            $this->gametypeModel->addGametype([
                'id' => $id,
                'name' => $name
            ]);
        }
    }

    /**
     * 获取gametype列表
     * @return unknown
     */
    public function getList()
    {
        $list = $this->gametypeModel->getGametypes();
        return $list;
    }
    
    public function getInfo()
    {
        $id = $this->request->getRequest('id', 'int', false);
        $info = $this->gametypeModel->getGametypeById($id);
        return $info;
    }

    /**
     * 获取用户当前所处的gametype值
     * @return Ambigous <unknown, multitype:>
     */
    public function getCurrentGametype()
    {
        $gametype = $this->env->get('gametype');
        if (empty($gametype)) {
            $info = $this->gametypeModel->getFirstGametype();
            $gametype = $info['id'];
        }
        
        return $gametype;
    }
    
    /**
     * 改变gametype值
     * @return boolean
     */
    public function ChangeGametype()
    {
        $gametype = $this->request->getRequest('gametype', 'int', false);
        if (! $this->gametypeModel->getGametypeById($gametype)) {
            $this->exception->throwUserException(sprintf($this->config->error('GAMETYPE_NOT_MATCH'), $gametype));
        }
        $this->func->setCookie($this->config->constant('gametypecookie'), $gametype);
        return true;
    }    
}