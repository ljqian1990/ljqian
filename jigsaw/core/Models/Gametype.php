<?php
namespace Jigsaw\Models;

class Gametype extends Base
{

    protected static $_self;

    protected $table = 'gametypes';

    public function getGametypes()
    {
        $ret = $this->db->select($this->table, '*');
        return $ret;
    }
    
    public function getFirstGametype()
    {
        $info = $this->db->selectOne($this->table, '*');
        return $info;
    }
    
    public function getGametypesByIds($ids)
    {
        $list = $this->db->select($this->table, '*', ['where'=>['id'=>['in'=>$ids]]]);
        return $list;
    }
    
    public function getGametypeById($id)
    {
        $info = $this->db->selectOne($this->table, '*', ['where'=>['id'=>$id]]);
        return $info;
    }
    
    public function delGametype($id)
    {
        $this->db->delete($this->table, ['where'=>['id'=>$id]]);
        return true;
    }
    
    /**
     * @deprecated
     * @param unknown $gametype
     */
    public function addGametype($gametype=[])
    {
        $this->db->insert($this->table, $gametype);
        return true;
    }
    
    public function editGametype($gametype=[], $id)
    {
        $this->db->update($this->table, $gametype, ['where'=>['id'=>$id]]);
        return true;
    }    
}