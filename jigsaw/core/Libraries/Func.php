<?php
/**
 * 公用方法静态类
 * 
 * @author qianlijia <qianlijia@ztgame.com>
 * @copyright Copyright (c) 巨人网络 (http://www.ztgame.com)
 * @package core.library
 * @version 1.0.0
 */
namespace Jigsaw\Libraries;

use Jigsaw\Services\Factory as SerFactory;

class Func
{

    public static function exec()
    {
        $c = strtolower(Request::getRequest('c', 'string', false));
        $f = strtolower(Request::getRequest('f', 'string', false));
        
        $map = Config::map($c, $f);
        
        $className = $map['c'];
        $funcName = $map['f'];
        
        Env::set('classname', $className);
        Env::set('funcname', $funcName);
        
        // if (ISDEBUG) {
        // Env::set('gametype', 5174);
        // } else {
        Env::set('gametype', self::getCookie(Config::constant('gametypecookie')));
        // }
        
        if (ISDEBUG && stripos($_SERVER['HTTP_REFERER'], 'localhost') !== false) {
            $_SESSION[Config::constant('usernamesession')] = 'chenxiayu';
        } else {
            $logineed = $map['l'];
            if ($logineed) {
                self::checkLogin();
            }
        }
        
        $class = "Jigsaw\\Controls\\$className";
        $object = new $class();
        
        // 是否需要验证当前用户具有对应项目的访问权限
        $needGametypeAuthCheck = $map['g'];
        if ($needGametypeAuthCheck) {
            $object->checkAuth();
        }
        
        $needAdminAuthCheck = $map['a'];
        if ($needAdminAuthCheck) {
            $object->checkAdmin();
        }
        
        $result = call_user_func([
            $object,
            $funcName
        ]);
        
        return $result;
    }

    /**
     * 判断当前是否处于登录状态
     */
    public static function isLogin()
    {
        if (empty($_SESSION[Config::constant('usernamesession')])) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 检查用户的登录状态，如果没有登录，则抛出异常
     */
    public static function checkLogin()
    {
        if (! self::isLogin()) {
            Exception::throwUnloginException(Config::error('UNLOGINED'));
        }
        return true;
    }

    /**
     * 获取当前登录者的username
     */
    public static function getCurrentUser()
    {
        self::checkLogin();
        return $_SESSION[Config::constant('usernamesession')];
    }

    public static function fname($id)
    {
        $md5 = md5($id);
        
        $str1 = substr($md5, 0, 4);
        
        $str2 = substr($md5, 10, 5);
        
        $str3 = substr($md5, 20, 3);
        
        return sprintf("%s-%s-%s-%03x.shtml", $str1, $str2, $str3, $id);
    }

    /**
     * 头像上传
     *
     * @param unknown $file            
     * @return string
     */
    public static function uploadImg($file)
    {
        $max = 2097152;
        $allowExts = array(
            'jpg',
            'jpeg',
            'gif',
            'png'
        );
        $allowTypes = array(
            'image/jpg',
            'image/jpeg',
            'image/gif',
            'image/png',
            'image/x-png',
            'image/pjpeg'
        );
        $uploadfile = new UploadFile($max, $allowExts, $allowTypes);
        $uploadfile->dateFormat = 'd';
        
        $uploadfile->thumb = false;
        $uploadfile->thumbMaxWidth = 800;
        $uploadfile->thumbMaxHeight = 800;
        
        if (ISDEBUG) {
            $dir = 'images/' . date('Y-m') . '/';
            $savePath = Config::constant('act_uploads_path') . $dir;
        } else {
            $gametype = Env::get(Config::constant('jigsaw_cur_gametype_info'), false);
            $dir = $gametype['sitepath'] . '/images/clp/' . date('Y-m') . '/';
            $savePath = Config::constant('act_uploads_path_pro') . $dir;
        }
        
        $ret = $uploadfile->uploadOne($file, $savePath);
        
        if (! $ret) {
            Exception::throwUserException($uploadfile->getErrorMsg());
        }
        $savename_arr = explode('/', $ret[0]['savename']);
        $savename = $savename_arr[0] . '/' . $savename_arr[1];
        
        if (ISDEBUG) {
            $url = ltrim(ltrim(rtrim(Config::constant('img_act_url'), '/'), 'https:'), 'http:') . Config::constant('act_uploads_uri') . $dir . $savename;
        } else {
            $url = ltrim(ltrim(rtrim($gametype['siteurl'], '/'), 'https:'), 'http:') . '/upload/' . $dir . $savename;
        }
        
        return $url;
    }

    public static function setCookie($key, $value, $time = 86400, $isPriDomain = false)
    {
        $_COOKIE[$key] = $value;
        if ($isPriDomain) {
            setcookie($key, $value, time() + $time, '/', Config::constant('domain_pri_url'));
        } else {
            setcookie($key, $value, time() + $time, '/');
        }
    }

    public static function getCookie($key)
    {
        return $_COOKIE[$key];
    }

    /**
     * 给一个二维数组以及一个索引，将二维数组的一维键名改成二位元素中的该索引的值
     * $arr[] = ['id'=>1, 'name'=>'ljqian1'];
     * $arr[] = ['id'=>2, 'name'=>'ljqian2'];
     * $arr[] = ['id'=>3, 'name'=>'ljqian3'];
     *
     * $ret = setFieldIndex($arr, 'name')
     * print_r($ret);
     *
     * 输出
     * $arr['ljqian1'=>['id'=>1, 'name'=>'ljqian1'], 'ljqian2'=>['id'=>2, 'name'=>'ljqian2'], 'ljqian3'=>['id'=>3, 'name'=>'ljqian3']];
     *
     * @param array $list            
     * @param string $index            
     */
    public static function setFieldIndex($list, $index)
    {
        if (empty($list)) {
            return [];
        }
        
        $ret = [];
        array_map(function ($val) use($index, &$ret) {
            $ret[$val[$index]] = $val;
        }, $list);
        return $ret;
    }
}