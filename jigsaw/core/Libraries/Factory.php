<?php
namespace Jigsaw\Libraries;

class Factory
{

    private static $_dbList = [];
    
    private static $_env = null;

    private static $_mcrypt = null;

    private static $_request = null;

    private static $_exception = null;

    private static $_config = null;

    private static $_cache = null;

    private static $_func = null;        
    
    /**
     * 加载缓存类
     */
    public static function loadCache()
    {
        if (! self::$_cache) {
            $cacheSet = Config::redis();
            self::$_cache = Cache::getInstance($cacheSet);
        }
        return self::$_cache;
    }

    /**
     * 载入配置类
     */
    public static function loadConfig()
    {
        if (! self::$_config) {
            self::$_config = new Config();
        }
        return self::$_config;
    }

    /**
     * 载入 db
     *
     * @param mixed $dbSet
     *            :数据库配置
     *            如果为空，则使用Common下的配置文件，如果单独配置请参考Common/configs/config.ini.php
     */
    public static function loadDB($dbSet = '')
    {
        if (empty($dbSet)) {
            $dbSet = Config::database();
        }
        $singleHash = md5(serialize($dbSet));
        
        if (isset(self::$_dbList[$singleHash])) {
            return self::$_dbList[$singleHash];
        }
        
        $dbObject = new DBMysql($dbSet);
        
        self::$_dbList[$singleHash] = $dbObject;
        
        return $dbObject;
    }


    /**
     * 载入异常类
     */
    public static function loadEnv()
    {
        if (! self::$_env) {
            self::$_env = new Env();
        }
        return self::$_env;
    }
    
    /**
     * 载入异常类
     */
    public static function loadException()
    {
        if (! self::$_exception) {
            self::$_exception = new Exception();
        }
        return self::$_exception;
    }

    /**
     * 载入公共方法类
     */
    public static function loadFunc()
    {
        if (! self::$_func) {
            self::$_func = new Func();
        }
        return self::$_func;
    }

    /**
     * 载入加解密类
     */
    public static function loadMcrypt()
    {
        if (! self::$_mcrypt) {
            self::$_mcrypt = new Mcrypt();
        }
        return self::$_mcrypt;
    }

    /**
     * 载入请求类
     */
    public static function loadRequest()
    {
        if (! self::$_request) {
            self::$_request = new Request();
        }
        return self::$_request;
    }

    /**
     * 载入curl类
     */
    public static function loadCurl()
    {
        return new Curl();
    }
}