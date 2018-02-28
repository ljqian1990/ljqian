<?php
/**
 * 异常处理类
 * 
 * @author qianlijia <qianlijia@ztgame.com>
 * @copyright Copyright (c) 巨人网络 (http://www.ztgame.com)
 * @package core.library
 * @version 1.0.0
 */
namespace Projectname\Libraries;

class Exception
{

    /**
     * 定义未登录错误
     */
    const UNLOGIN_CODE = 1;

    /**
     * 定义系统错误
     */
    const SYS_CODE = 2;

    /**
     * 定义用户操作错误
     */
    const USER_CODE = 3;

    public static function throwUnloginException($msg)
    {
        self::throwException($msg, self::UNLOGIN_CODE);
    }

    public static function throwSystemException($msg)
    {
        self::throwException($msg, self::SYS_CODE);
    }

    public static function throwUserException($msg)
    {
        self::throwException($msg, self::USER_CODE);
    }

    private static function throwException($msg, $code)
    {
        throw new \Exception($msg, $code);
    }
}