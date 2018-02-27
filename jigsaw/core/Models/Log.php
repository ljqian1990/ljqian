<?php
namespace Jigsaw\Models;

class Log extends Base
{

    protected static $_self;

    protected $table = 'logs';

    public function write()
    {
        if ($this->func->isLogin()) {
            $gametype = $this->env->get('gametype');
            $classname = $this->env->get('classname');
            $funcname = $this->env->get('funcname');
            $author = $this->func->getCurrentUser();
            $time = date('Y-m-d H:i:s');
            
            $log = [
                'gametype' => $gametype,
                'classname' => $classname,
                'funcname' => $funcname,
                'operator' => $author,
                'datetime' => $time
            ];
            
            $this->db->insert($this->table, $log);
        }
    }
}