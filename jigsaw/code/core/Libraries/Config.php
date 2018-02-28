<?php
/**
 * 获取配置封装类
 * 
 * @author qianlijia <qianlijia@ztgame.com>
 * @copyright Copyright (c) 巨人网络 (http://www.ztgame.com)
 * @package core.library
 * @version 1.0.0
 */
namespace Projectname\Libraries;

class Config
{

    private static $configs = [];
    
    public static function __callStatic($name, $arguments)
    {
        if (isset(self::$configs[$name])) {
            $config = self::$configs[$name];
        } else {
            $filename = CONFIGPATH . $name . '.php';
            if (! file_exists($filename)) {
                Exception::throwSystemException(sprintf(self::error('CONFIG_FILE_NOT_EXIST'), $name));
            }
            
            $config = require $filename;
        }
        
        
        if (empty($arguments)) {
            return $config;
        }
        
        if (count($arguments) == 1) {
            if (!isset($config[$arguments[0]])) {
                Exception::throwSystemException(sprintf(self::error('CONFIG_CONTENT_NOT_EXIST'), $arguments[0], $name));
            }
            return $config[$arguments[0]];
        }
        
        if (! isset($config[$arguments[0]][$arguments[1]])) {
            Exception::throwSystemException(sprintf(self::error('CONFIG_CONTENT_NOT_EXIST'), $arguments[0].'-'.$arguments[1], $name));
        }
        return $config[$arguments[0]][$arguments[1]];
    }
    
    public function __call($name, $arguments)
    {
        return $this->__callStatic($name, $arguments);
    }
}