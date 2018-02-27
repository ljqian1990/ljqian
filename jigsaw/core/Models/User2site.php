<?php
namespace Jigsaw\Models;

class User2site extends Base
{

    protected static $_self;

    protected $table = 'user2site';
    
    public function add($uid, $gametypeid)
    {
        $this->db->insert($this->table, ['uid'=>$uid, 'gametypeid'=>$gametypeid]);
        return true;
    }
    
    public function delByUid($uid)
    {
        $this->db->delete($this->table, ['where'=>['uid'=>$uid]]);
        return true;
    }
    
    public function delByGametypeid($gametypeid)
    {
        $this->db->delete($this->table, ['where'=>['gametypeid'=>$gametypeid]]);
        return true;
    }
    
    public function delByUidAndGametypeid($uid, $gametypeid)
    {
        $this->db->delete($this->table, ['where'=>['uid'=>$uid, 'gametypeid'=>$gametypeid]]);
        return true;
    }
    
    public function findAll()
    {
        $ret = $this->db->select($this->table, '*');
        return $ret;
    }
    
    public function findByUid($uid)
    {
        $ret = $this->db->select($this->table, '*', ['where'=>['uid'=>$uid]]);
        return $ret;
    }
    
    public function findByGametypeid($gametypeid)
    {
        $ret = $this->db->select($this->table, '*', ['where'=>['gametypeid'=>$gametypeid]]);
        return $ret;
    }
    
    public function findByUidAndGametypeid($uid, $gametypeid)
    {
        $ret = $this->db->select($this->table, '*', ['where'=>['uid'=>$uid, 'gametypeid'=>$gametypeid]]);
        return $ret;
    }
    
    public function getFirstGametypeIdByUid($uid)
    {
        $info = $this->db->selectOne($this->table, 'gametypeid', ['where'=>['uid'=>$uid]]);
        return $info['gametypeid'];
    }
}