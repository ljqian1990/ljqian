<?php
namespace Projectname\Models;

use Projectname\Libraries\Factory;

class Base
{

    protected $db;

    protected $table;

    public static function getInstance()
    {
        if (! static::$_self) {
            static::$_self = new static();
        }
        return static::$_self;
    }

    private function __construct($dbSet = '')
    {
        $this->db = Factory::loadDB($dbSet);
    }
    
    protected function fetchAll($sql, $bind)
    {
        $ret = $this->db->fetchAll($sql, $bind);
        return $ret;
    }
    
    protected function count($where=array())
    {
        $count = $this->db->count($this->getTable(), '*', array('where'=>$where));
        return $count;
    }
    
    protected function select($where=array(), $fields='*', $order='`id` desc', $offset=0, $size=9999, $group='')
    {
        $options = array('where'=>$where, 'order'=>$order, 'limit'=>"$offset,$size");
        if (!empty($group)) {
            $options['group'] = $group;
        }
        $list = $this->db->select($this->getTable(), $fields, $options);
        return $list;
    }
    
    protected function selectOne($where, $fields='*', $order='`id` desc')
    {
        $ret = $this->db->selectOne($this->getTable(), $fields, array('where'=>$where, 'order'=>$order));
        if ($fields !== '*' && strpos($fields, ',')===false) {
            $fields = trim($fields, '`');
            return $ret[$fields];
        }
        return $ret;
    }
    
    protected function insert($data, $isReplace = false)
    {
        try {
            if ($isReplace) {
                $lastId = $this->db->replace($this->getTable(), $data);
            } else {
                $lastId = $this->db->insert($this->getTable(), $data);
            }
            return $lastId;
        } catch (\Exception $ex) {
            Exception::throwSysException(Error::DB_ERROR, __METHOD__);
        }
    }
    
    protected function update($data, $where)
    {
        try {
            $this->db->update($this->getTable(), $data, array('where'=>$where));
        } catch (\Exception $ex) {
            Exception::throwSysException(Error::DB_ERROR, __METHOD__);
        }
    }
    
    protected function delete($where)
    {
        try {
            $this->db->delete($this->getTable(), array('where'=>$where));
        } catch (\Exception $ex) {
            Exception::throwSysException(Error::DB_ERROR, __METHOD__);
        }
    }
    
    private function getTable()
    {
        if (empty($this->table)) {
            Exception::throwSysException(Error::DB_ERROR, __METHOD__);
        }
        return $this->table;
    }
}