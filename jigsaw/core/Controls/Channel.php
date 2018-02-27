<?php
namespace Jigsaw\Controls;

use Jigsaw\Models\Channel as ChannelModel;

class Channel extends Base
{

    private $channelModel = null;

    public function __construct()
    {
        $this->channelModel = ChannelModel::getInstance();
        parent::__construct();
    }

    public function save()
    {
        $flag = $this->request->getRequest('flag', 'string', false);
        $name = $this->request->getRequest('name', 'string', false);
        $id = $this->request->getRequest('id', 'int', true);
        
        $channel = [
            'flag' => $flag,
            'name' => $name
        ];
        
        if (empty($id)) {
            $this->channelModel->addChannel($channel);
        } else {
            $this->channelModel->editChannel($channel, $id);
        }
        
        return true;
    }
    
    public function remove()
    {
        $id = $this->request->getRequest('id', 'int', false);
        $this->channelModel->delChannel($id);
        
        return true;
    }
    
    /**
     * 获取channel列表
     * @return unknown
     */
    public function getList()
    {
        $list = $this->channelModel->getChannels();
        return $list;
    }
    
    public function getInfo()
    {
        $id = $this->request->getRequest('id', 'int', false);
        $info = $this->channelModel->getChannelById($id);
        
        return $info;
    }
}