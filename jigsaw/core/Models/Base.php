<?php
namespace Jigsaw\Models;

use Jigsaw\Libraries\Factory;

class Base
{

    protected $db;

    protected $cache;
    
    protected $func;
    
    protected $env;

    protected $table;

    public static function getInstance()
    {
        if (! static::$_self) {
            static::$_self = new static();
        }
        return static::$_self;
    }

    private function __construct()
    {
        $this->db = Factory::loadDB();
        $this->cache = Factory::loadCache();
        $this->func = Factory::loadFunc();
        $this->env = Factory::loadEnv();
    }
}