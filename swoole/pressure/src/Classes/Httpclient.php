<?php
namespace Pressure\Classes;

use Pressure\Callback\Base as CallbackBase;
use Pressure\Classes\Client as ClientBase;
use Closure;
use swoole_http_client;

class Httpclient extends ClientBase
{
    private $host = '';
    private $header = [];
    private $uri = '';
    private $method = 'GET';
    private $params = [];    

    public function __construct(Parse $oParse, CallbackBase $oCallback)
    {
        parent::__construct($oParse, $oCallback);
        
        $this->setHost($oParse->getHost());
        $this->setUri($oParse->getUri());
        $this->setMethod($oParse->getMethod());
        $this->setParams($oParse->getParams());
        
        return $this;
    }

    public function send()
    {
        $this->cli = new swoole_http_client($this->getIp(), $this->getPort());
        
        $host = $this->getHost();
        if (!empty($host)) {
            $hostHeader = ['Host'=>$this->getHost()];      
            $this->cli->setHeaders($hostHeader);
        }
        
        $this->cli->set([
            'timeout' => $this->getTimeout()
        ]);
        
        $uri = $this->getUri();
        $method = $this->getMethod();
        if ($method == 'GET') {
            $this->cli->get($uri, function(swoole_http_client $cli) {                
                $this->getOcallback()->callback($this, $cli);
                $cli->close();
            });
        } elseif ($method == 'POST') {
            $this->cli->post($uri, $this->getParams(), function (swoole_http_client $cli) {
                if ($cli->statusCode == 200) {
                    echo "OK\r\n";
                } else {
                    echo "Error\r\n";
                }
                $this->getOcallback()->callback($cli);
                $cli->close();
            });
        }
    }

    private function setHost(String $host)
    {
        $this->host = $host;
        return $this;
    }

    private function setUri(String $uri)
    {
        $this->uri = $uri;
        return $this;
    }

    private function setMethod(String $method)
    {
        $this->method = $method;
        return $this;
    }

    private function setParams(Array $params)
    {
        $this->params = $params;
        return $this;
    }

    private function getHost()
    {
        return $this->host;
    }

    private function getUri()
    {
        if (empty($this->uri)) {
            throw new Exception('uri不能为空');
        }
        return $this->uri;
    }

    private function getMethod()
    {
        if (!in_array($this->method, ['GET', 'POST'])) {
            throw new Exception('目前请求方式只支持GET或POST');
        }
        return $this->method;
    }

    private function getParams()
    {
        return $this->method;
    }
}