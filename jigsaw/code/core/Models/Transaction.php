<?php
namespace Projectname\Models;

/**
 * 事务支持类
 * @author qianlijia
 * @since 2017.05.10
 */
class Transaction extends Base
{
    protected static $_self;
    
    /**
     * 开始事务
     */
    public function start()
    {
        $this->db->beginTransaction();
    }
    
    /**
     * 提交事务
     */
    public function commit()
    {
        $this->db->commit();
    }
    
    /**
     * 回滚事务
     */
    public function rollback()
    {
        $this->db->rollBack();
    }
}