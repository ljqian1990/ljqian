<?php
/**
 * 表单验证类
 *
 * @author qianlijia
 *
 */
namespace Projectname\Libraries;

class Request
{
    
    /**
     * 获取request参数值
     *
     * @param string $paramName
     *            参数名
     * @param string $defaultValue
     *            默认值
     * @param string $format
     *            参数格式，int、string、array
     * @param string $canEmpty
     *            参数是否可以为空
     * @throws \Exception
     */
    public static function getRequest($paramName, $format = 'string', $canEmpty = true, $defaultValue = '')
    {
        if (empty($paramName)) {
            Exception::throwUserException(sprintf(Config::error('INVALID_ARGUMENT'), $paramName));
        }
        
        $param = $_REQUEST[$paramName];
        if ($canEmpty && empty($param)) {
            return self::getDefaultValueByFormat($format, $defaultValue);
        }
        if (! $canEmpty && empty($param)) {
            Exception::throwUserException(sprintf(Config::error('CANT_EMPTY'), $paramName));
        }
        
        $param = self::getValueByFormat($format, $param);
        if ($param === '') {
            if ($canEmpty) {
                return $defaultValue;
            } else {
                Exception::throwUserException(sprintf(Config::error('CANT_EMPTY'), $paramName));
            }
        }
        
        return $param;
    }

    private static function getDefaultValueByFormat($format, $value)
    {
        switch ($format) {
            case 'int':
                $ret = intval($value);
                break;
            case 'array':
                $ret = (array) $value;
                break;
            case 'string':
            case 'email':
            case 'qq':
            case 'url':
            case 'phone':
            case 'idcard':
                $ret = (string) $value;
                break;
        }
        return $ret;
    }

    private static function getValueByFormat($format, $value)
    {
        $ret = '';
        
        switch ($format) {
            case 'int':
                $value = trim($value);
                $ret = intval($value);
                break;
            case 'array':
                $ret = (array) $value;
                break;
            case 'string':                
                $ret = $value;
                break;
            case 'email':
                $value = trim($value);
                if (! self::isEmail($value)) {
                    Exception::throwUserException(Config::error(EMAIL_FORMAT_ERROR));
                }
                $ret = $value;
                break;
            case 'qq':
                $value = trim($value);
                if (! self::isQQ($value)) {
                    Exception::throwUserException(Config::error(QQ_FORMAT_ERROR));
                }
                $ret = $value;
                break;
            case 'url':
                $value = trim($value);
                if (! self::isUrl($value)) {
                    Exception::throwUserException(Config::error(URL_FORMAT_ERROR));
                }
                $ret = $value;
                break;
            case 'phone':
                $value = trim($value);
                if (! self::isPhone($value)) {
                    Exception::throwUserException(Config::error(PHONE_FORMAT_ERROR));
                }
                $ret = $value;
                break;
            case 'idcard':
                $value = trim($value);
                if (! Idcard::validation_filter_id_card($value)) {
                    Exception::throwUserException(Config::error(IDCARD_FORMAT_ERROR));
                }
                $ret = $value;
                break;
        }
        return $ret;
    }

    public static function isQQ($qq)
    {
        return preg_match("/^[1-9][0-9]{4,11}$/", $qq);
    }

    public static function isPhone($phone)
    {
        return preg_match("/1\d{10}$/", $phone);
    }

    public static function isEmail($email)
    {
        return preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/", $email);
    }

    public static function isUrl($url)
    {
        return preg_match("/(http(s)?|ftp|file)?(:\/\/)?([\da-z0-9-\.]+)\.([a-z0-9]{2,6})([\/\w \.-?:&%-=]*)*\/?/", $url);
    }

    /**
     * 判断一个字符串是否只由数字和英文构成
     * 
     * @param unknown $str            
     */
    public static function isEnglishOrNum($str)
    {
        return preg_match("/^[a-zA-Z0-9]+$/", $str);
    }

    /**
     * 判断一个字符串是否只由数字、英文、空格构成
     * 
     * @param unknown $str            
     */
    public static function isEnglishOrNumOrSpace($str)
    {
        return preg_match("/^[a-zA-Z0-9\s]+$/", $str);
    }
}