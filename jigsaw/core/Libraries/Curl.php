<?php
/**
 * curl类封装
 * @author qianlijia<qianlijia@ztgame.com>
 * @version 1.0
 * @date    2017.09.20
 */
namespace Jigsaw\Libraries;

/**
 * curl封装类
 *
 * @author qianlijia
 *         $oCurl = new Curl();
 *         $json = $oCurl->setUrl(self::URL)
 *         ->setMethod('POST')
 *         ->setData($params)
 *         ->httpRequest();
 *         根据实际需求设置具体的参数
 *        
 */
class Curl
{

    /**
     *
     * @var curl对象实体
     */
    private $oCurl = null;

    /**
     *
     * @var 请求方式
     */
    private $method = 'GET';

    /**
     *
     * @var 被请求对象的url地址
     */
    private $url = '';

    /**
     *
     * @var 请求发起时需要传递给被请求对象的数据 以键值对形式存放
     *      $data = ['name'=>'qianlijia', 'email'=>'qianlijia@ztgame.com'];
     */
    private $data = [];

    /**
     *
     * @var 请求发起时需要传递给被请求对象的头部信息 $header = ["POST ".$page." HTTP/1.0", "Content-type: text/xml;charset=\"utf-8\""];
     */
    private $header = [];

    /**
     *
     * @var 请求发起时需要传递给被请求对象的cookie值 $cookie = ['name'=>'qianlijia', 'email'=>'qianlijia@ztgame.com'];
     */
    private $cookie = [];

    /**
     *
     * @var 超时时间
     */
    private $timeout = 5;

    /**
     *
     * @var 请求链接超时时间
     */
    private $connecttimeout = 5;

    /**
     *
     * @var 是否返回头部信息
     */
    private $returnheader = false;

    /**
     *
     * @var 被允许的请求method方式
     */
    private $legalMethods = [
        'GET',
        'HEAD',
        'PUT',
        'POST',
        'TRACE',
        'OPTIONS',
        'DELETE'
    ];

    public function setMethod($method)
    {
        $method = strtoupper($method);
        if (! in_array($method, $this->legalMethods)) {
            Exception::throwSystemException(sprintf(Config::error('METHOD_NOT_ALLOW'), $method));
        }
        $this->method = $method;
        return $this;
    }

    public function setUrl($url)
    {
        if (! $this->isUrl($url)) {
            Exception::throwSystemException(Config::error('URL_FORMAT_ERROR'));
        }
        $this->url = $url;
        return $this;
    }

    public function setData($data)
    {
        $data = (array) $data;
        $this->data = $data;
        return $this;
    }

    public function setHeader($header)
    {
        $header = (array) $header;
        $this->header = $header;
        return $this;
    }

    public function setCookie($cookie)
    {
        $cookiestr = '';
        if (! empty($cookie)) {
            $cookiestr_arr = [];
            foreach ($cookie as $key => $value) {
                $cookiestr_arr[] = $key . '=' . $value;
            }
            $cookiestr = implode('; ', $cookiestr_arr);
        }
        $this->cookie = $cookiestr;
        return $this;
    }

    public function setTimeout($timeout)
    {
        $timeout = (int) $timeout;
        $this->timeout = $timeout;
        return $this;
    }

    public function setConnecttimeout($connecttimeout)
    {
        $connecttimeout = (int) $connecttimeout;
        $this->connecttimeout = $connecttimeout;
        return $this;
    }

    public function setReturnheader($returnheader)
    {
        if (! is_bool($returnheader)) {
            Exception::throwSystemException(Config::error('RETURNHEADER_FORMAT_ERROR'));
        }
        $this->returnheader = $returnheader;
        return $this;
    }

    public function httpRequest()
    {
        $this->oCurl = curl_init();
        
        $url = $this->getUrl();
        if (stripos($url, 'https://') !== false) {
            curl_setopt($this->oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($this->oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($this->oCurl, CURLOPT_SSLVERSION, 1); // CURL_SSLVERSION_TLSv1
        }
        curl_setopt($this->oCurl, CURLOPT_URL, $url);
        
        $header = $this->getHeader();
        if ($header) {
            curl_setopt($this->oCurl, CURLOPT_HTTPHEADER, $header);
        }
        $returnheader = $this->getReturnheader();
        if ($returnheader) {
            curl_setopt($this->oCurl, CURLOPT_HEADER, $returnheader);
        }
        
        curl_setopt($this->oCurl, CURLOPT_CUSTOMREQUEST, $this->getMethod());
        $data = $this->getData();
        if ($data) {
            curl_setopt($this->oCurl, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        
        $cookie = $this->getCookie();
        if ($cookie) {
            curl_setopt($this->oCurl, CURLOPT_COOKIE, $cookie);
        }
        
        // 指定使用ipv4来解析dns，防止curl默认使用ipv6去解析dns造成请求超时
        if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
            curl_setopt($this->oCurl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }
        
        curl_setopt($this->oCurl, CURLOPT_TIMEOUT, $this->getTimeout());
        curl_setopt($this->oCurl, CURLOPT_CONNECTTIMEOUT, $this->getConnecttimeout());
        curl_setopt($this->oCurl, CURLOPT_RETURNTRANSFER, 1);
        
        $sContent = curl_exec($this->oCurl);
        
        return $sContent;
    }

    private function getMethod()
    {
        if (empty($this->method)) {
            Exception::throwSystemException(Config::error('METHOD_EMPTY_ERROR'));
        }
        return $this->method;
    }

    private function getUrl()
    {
        if (empty($this->url)) {
            Exception::throwSystemException(Config::error('URL_EMPTY_ERROR'));
        }
        return $this->url;
    }

    private function getData()
    {
        return $this->data;
    }

    private function getHeader()
    {
        return $this->header;
    }

    private function getCookie()
    {
        return $this->cookie;
    }

    private function getTimeout()
    {
        return $this->timeout;
    }

    private function getConnecttimeout()
    {
        return $this->connecttimeout;
    }

    private function getReturnheader()
    {
        return (int) $this->returnheader;
    }

    /*
     * 判断是否是URL
     */
    private function isUrl($url)
    {
        if (parse_url($url)) {
            return true;
        }
        return false;
    }
}