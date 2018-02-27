<?php
namespace Jigsaw\Models;

class Project extends Base
{

    protected static $_self;

    protected $table = 'projects';

    public function addProject($project)
    {
        $project['author'] = $this->func->getCurrentUser();
        $project['gametype'] = $this->getGametype();
        $project['datetime'] = date('Y-m-d H:i:s');
        $project['updatetime'] = date('Y-m-d H:i:s');
        
        return $this->db->insert($this->table, $project);
    }

    public function editProject($id, $project)
    {
        $gametype = $this->getGametype();
        $this->db->update($this->table, $project, [
            'where' => [
                'id' => $id,
                'gametype' => $gametype
            ]
        ]);
        return true;
    }

    public function delProject($id)
    {
        $gametype = $this->getGametype();
        $this->db->delete($this->table, [
            'where' => [
                'id' => $id,
                'gametype' => $gametype
            ]
        ]);
        return true;
    }

    public function getProjects($offset, $size, $channelid = '',$author='')
    {
        $gametype = $this->getGametype();
        $where = ['gametype'=>$gametype];
        if (!empty($author)) {
            $where['author'] = $author;
        }
        if (!empty($channelid)) {
            $where['channelid'] = $channelid;
        }
        $list = $this->db->select($this->table, '`id`,`channelid`,`title`,`keyword`,`desc`,`csslink`,`jslink`,`bgcolor`,`author`,`gametype`,`htmlfile`,`datetime`,`updatetime`', [
            'where' => $where,
            'order' => 'id desc',
            'limit' => "$offset,$size"
        ]);
        return $list;
    }
    
    public function getCount($channelid = '', $author='')
    {
        $gametype = $this->getGametype();
        $where = ['gametype'=>$gametype];
        if (!empty($author)) {
            $where['author'] = $author;
        }
        if (!empty($channelid)) {
            $where['channelid'] = $channelid;
        }
        $count = $this->db->count($this->table, '*', ['where'=>$where]);
        return $count;
    }

    public function getInfo($id)
    {
        $gametype = $this->getGametype();
        $info = $this->db->selectOne($this->table, '*', [
            'where' => [
                'gametype' => $gametype,
                'id' => $id
            ]
        ]);
        return $info;
    }    

    private function getGametype()
    {
        $gametype = $this->env->get('gametype', false);
        return $gametype;
    }
}