<?php
/**
 * 公用方法静态类
 * 
 * @author qianlijia <qianlijia@ztgame.com>
 * @copyright Copyright (c) 巨人网络 (http://www.ztgame.com)
 * @package core.library
 * @version 1.0.0
 */
namespace Projectname\Libraries;

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
        
        $class = "Projectname\\Controls\\$className";
        $object = new $class();
        
        $result = call_user_func([
            $object,
            $funcName
        ]);
        
        return $result;
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
            $gametype = Env::get(Config::constant('projectname_cur_gametype_info'), false);
            $dir = $gametype['sitepath'] . '/images/projectname/' . date('Y-m') . '/';
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
    
    private static function getFieldFromList($list, $field)
    {
        $data = array();
        if (! empty($list)) {
            foreach ($list as $key => $value) {
                array_push($data, $value[$field]);
            }
        }
        $data = array_unique($data);
        if (count($data) == 1 && $data[0] == 0) {
            return array();
        }
        return $data;
    }
    
    /**
     * 循环arr，将paramold中的字段转为paramnew的字段，并返回结果，适用于二维数组
     *
     * $arr[] = ['name'=>'ljqian', 'age'=>28, 'sex'=>1, 'address'=>'11111'];
     * $arr[] = ['name'=>'ljqian1', 'age'=>281, 'sex'=>11, 'address'=>'111111'];
     * $arr[] = ['name'=>'ljqian2', 'age'=>282, 'sex'=>12, 'address'=>'111112'];
     *
     * $list = extractArray($arr, ['namen', 'agen', 'addn'], ['name', 'age', 'address']);
     * print_r($list);
     *
     * 输出
     * Array
     * (
     * [0] => Array
     * (
     * [namen] => ljqian
     * [agen] => 28
     * [addn] => 11111
     * )
     * [1] => Array
     * (
     * [namen] => ljqian1
     * [agen] => 281
     * [addn] => 111111
     * )
     * [2] => Array
     * (
     * [namen] => ljqian2
     * [agen] => 282
     * [addn] => 111112
     * )
     * )
     *
     * @param array $arr
     *            原数组，只能为二维
     * @param array $paramnew
     *            新数组的元素
     * @param array $paramold
     *            老数组的元素
     * @throws \Exception
     */
    public static function extractTwoDimensionalArray($arr = [], $paramnew = [], $paramold = [])
    {
        if (empty($arr) || empty($paramnew) || empty($paramold)) {
            return [];
        }
    
        if (count($paramnew) != count($paramold)) {
            Exception::throwSysException(Error::EXTRACTARRAY_ERROR, __METHOD__);
        }
    
        $list = [];
        foreach ($arr as $key => $val) {
            array_map(function ($pn, $po) use($val, $key, &$list) {
                $list[$key][$pn] = $val[$po];
            }, $paramnew, $paramold);
        }
        return $list;
    }
    
    /**
     * 循环arr，将paramold中的字段转为paramnew的字段，并返回结果，适用于一维数组
     *
     * $arr = ['name'=>'ljqian', 'age'=>28, 'sex'=>1, 'address'=>'11111'];
     * $list = extractOneDimensionalArray($arr, ['namen', 'sexn'], ['name','sex']);
     * print_r($list);
     *
     * 输出
     * Array
     * (
     *     [namen] => ljqian
     *     [sexn] => 1
     * )
     *
     * @param array $arr
     *            原数组，只能为一维
     * @param array $paramnew
     *            新数组的元素
     * @param array $paramold
     *            老数组的元素
     * @throws \Exception
     */
    public static function extractOneDimensionalArray($arr = [], $paramnew = [], $paramold = [])
    {
        if (empty($arr) || empty($paramnew) || empty($paramold)) {
            return [];
        }
    
        if (count($paramnew) != count($paramold)) {
            Exception::throwSysException(Error::EXTRACTARRAY_ERROR, __METHOD__);
        }
    
        $list = [];
        array_map(function ($pn, $po) use($arr, &$list) {
            $list[$pn] = $arr[$po];
        }, $paramnew, $paramold);
        return $list;
    }
    
    /**
     * 过滤整型数组，将数组中的非整型数据给过滤掉
     *
     * @param array $arr
     * @return array $arr
     */
    public static function filterIntArray($arr)
    {
        if (empty($arr)) {
            return $arr;
        }
        array_walk($arr, function (&$var) {
            $var = intval($var);
        });
        $arr = array_filter($arr, function ($var) {
            return $var !== 0;
        });
        return $arr;
    }
    
    public static function getClientIP()
    {
        if(!empty($_SERVER["HTTP_CLIENT_IP"]))
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
        {
            $cips = explode(",",$_SERVER["HTTP_X_FORWARDED_FOR"]);
            $cip = array_shift($cips);
        }
        else if(!empty($_SERVER["REMOTE_ADDR"]))
            $cip = $_SERVER["REMOTE_ADDR"];
        else
            $cip = "0.0.0.0";
    
        return trim($cip);
    }
    
    /**
     * 将时间格式化
     *
     * @param unknown $date
     */
    public static function formatDate($date, $format='m/d')
    {
        $timediff = time() - strtotime($date);
        if ($timediff < 60) {
            $formatdate = $timediff . '秒前';
        } elseif ($timediff < 3600) {
            $formatdate = intval($timediff / 60) . '分前';
        } elseif ($timediff < 86400) {
            $formatdate = intval($timediff / 3600) . '小时前';
        } else {
            $formatdate = date($format, strtotime($date));
        }
        return $formatdate;
    }
}