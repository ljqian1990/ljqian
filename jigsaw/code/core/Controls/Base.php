<?php
namespace Projectname\Controls;

use Projectname\Libraries\Factory as LibFactory;
use Projectname\Services\Factory as SerFactory;
use Projectname\Models\Transaction as TransactionModel;

class Base
{

    protected $config;

    protected $env;

    protected $exception;

    protected $func;

    protected $mcrypt;

    protected $request;
    
    protected $cache;
    
    private $transactionModel = null;

    public function __construct()
    {
        $this->config = LibFactory::loadConfig();
        $this->env = LibFactory::loadEnv();
        $this->exception = LibFactory::loadException();
        $this->func = LibFactory::loadFunc();        
        $this->request = LibFactory::loadRequest();
        $this->cache = LibFactory::loadCache();
        
        $this->setTransactionModel();
    }

    public function loadService($servicename)
    {
        $method_name = 'load' . ucfirst($servicename);
        if (! method_exists(SerFactory, $method_name)) {
            $this->exception->throwSystemException(sprintf($this->config->error('SERVICE_NOT_FOUND'), $servicename));
        }
        $serviceobj = call_user_func([
            SerFactory,
            $method_name
        ]);
        return $serviceobj;
    }
    
    /**
     * 保存缓存
     * @param unknown $key
     * @param unknown $val
     * @param number $et
     */
    protected function setCache($key, $val=[], $et=5)
    {
        if (empty($key)) {
            Exception::throwSysException(Error::SYS_CANNOT_EMPTY, __METHOD__);
        }
    
        $expire = $et+mt_rand(0, 5);
        $str = empty($val) ? '' : serialize(['val'=>$val, 'expire'=>time()+$expire]);
        $this->mc->set($key, $str, $expire*2);
    }
    
    /**
     * 获取缓存
     * @param unknown $key
     */
    protected function getCache($key)
    {
        if (Config::IS_DEBUG)  {
            return [];
        }
        if (empty($key)) {
            Exception::throwSysException(Error::SYS_CANNOT_EMPTY, __METHOD__);
        }
        $str = $this->mc->get($key);
        if (empty($str)) {
            return [];
        }
        $arr = unserialize($str);
    
        // 平滑过渡用
        if (! is_array($arr) || ! isset($arr['expire'])) {
            return $arr;
        }
    
        if ($arr['expire'] > time()) {
            // 尚未过期
            return $arr['val'];
        } else {
            // 过期了
            $lock = self::MEMCACHE_EXPIRE_LOCK . $key;
            if ($this->mc->add($lock, 1, self::MEMCACHE_EXPIRE_LOCK_TIME)) {
                return [];
            } else {
                return $arr['val'];
            }
        }
    }

    protected function startTransaction()
    {
        $this->transactionModel->start();
    }
    
    protected function commitTransaction()
    {
        $this->transactionModel->commit();
    }
    
    protected function rollBack()
    {
        $this->transactionModel->rollback();
    }
    
    private function setTransactionModel()
    {
        $this->transactionModel = TransactionModel::getInstance();
    }
}