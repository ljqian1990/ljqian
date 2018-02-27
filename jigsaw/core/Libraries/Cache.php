<?php
namespace Jigsaw\Libraries;

use Redis;

class Cache
{

    private $_ConnectPool = [];

    private $_Link = null;

    private $_Configs = [];

    private static $_instance = null;

    public static function getInstance($configs = '')
    {
        if (self::$_instance === null) {
            if (! is_array($configs))
                trigger_error("Redis 配置参数有误", E_USER_ERROR);
            self::$_instance = new self($configs);
        }
        return self::$_instance;
    }

    private function __construct($configs)
    {
        $this->setConfigs($configs);
    }

    private function setConfigs($configs)
    {
        $this->_Configs = $configs;
    }

    private function _setCurrentConnect($isMaster = false)
    {
        if ($isMaster) {
            $_config = $this->getMasterConfig();
            $key = 'master';
        } else {
            $_config = $this->getConfig();
            $key = 'slave';
        }
        
        if (! isset($this->_ConnectPool[$key])) {
            $this->_ConnectPool[$key] = $this->_getConnect($_config);
        }
        
        return $this->_Link = $this->_ConnectPool[$key];
    }

    private function getMasterConfig()
    {
        return $this->_Configs['master'];
    }

    private function getSlaveConfig()
    {
        return $this->_Configs['slave'][array_rand($this->_Configs['slave'])];
    }

    private function getConfig()
    {
        $configs = $this->_Configs['slave'];
        $configs[] = $this->_Configs['master'];
        return $configs[array_rand($configs)];
    }

    private function _getConnect($config)
    {
        $redis = new Redis();
        if (empty($config['timeout'])) {
            $config['timeout'] = 5;
        }
        $redis->connect($config['host'], $config['port'], $config['timeout']) || trigger_error("无法链接 Redis!", E_USER_ERROR);
        return $redis;
    }

    public function set($key, $val, $ct = 0)
    {
        $this->_setCurrentConnect(true);
        if (empty($ct)) {
            return $this->_Link->set($key, $val);
        }
        return $this->_Link->setex($key, $ct, $val);
    }

    public function get($key)
    {
        $this->_setCurrentConnect(false);
        return $this->_Link->get($key);
    }

    public function add($key, $val, $ct = 0)
    {
        $this->_setCurrentConnect(true);
        return $this->_Link->set($key, $val, [
            'nx',
            'ex' => $ct
        ]);
    }

    public function incr($key, $offset = 1)
    {
        $this->_setCurrentConnect(true);
        return $this->_Link->incrBy($key, $offset);
    }

    public function decr($key, $offset = 1)
    {
        $this->_setCurrentConnect(true);
        return $this->_Link->decrBy($key, $offset);
    }

    public function close()
    {
        $this->_Link->close();
    }
}