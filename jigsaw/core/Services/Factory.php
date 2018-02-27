<?php
namespace Jigsaw\Services;

class Factory
{

    private static $sso = null;

    private static $filesync = null;

    private static $cdnsync = null;

    /**
     * 加载sso服务
     */
    public static function loadSso()
    {
        if (! self::$sso) {
            self::$sso = new Sso();
        }
        return self::$sso;
    }

    /**
     * 加载文件同步服务
     */
    public static function loadFilesync()
    {
        if (! self::$filesync) {
            self::$filesync = new FileSync();
        }
        return self::$filesync;
    }
    
    /**
     * 加载cdn推送服务
     */
    public static function loadCdnsync()
    {
        if (! self::$cdnsync) {
            self::$cdnsync = new CdnSync();
        }
        return self::$cdnsync;
    }
}