<?php
namespace Jigsaw\Models;

class Channel extends Base
{

    protected static $_self;

    protected $table = 'channels';

    public function getChannels()
    {
        $ret = $this->db->select($this->table, '*');
        return $ret;
    }    
    
    public function getChannelsByIds($ids)
    {
        $list = $this->db->select($this->table, '*', ['where'=>['id'=>['in'=>$ids]]]);
        return $list;
    }
    
    public function getChannelById($id)
    {
        $info = $this->db->selectOne($this->table, '*', ['where'=>['id'=>$id]]);
        return $info;
    }
    
    public function delChannel($id)
    {
        $this->db->delete($this->table, ['where'=>['id'=>$id]]);
        return true;
    }
    
    /**
     * @deprecated
     * @param unknown $channel
     */
    public function addChannel($channel=[])
    {
        $this->db->insert($this->table, $channel);
    }
    
    public function editChannel($channel=[], $id)
    {
        $this->db->update($this->table, $channel, ['where'=>['id'=>$id]]);
        return true;
    }
}