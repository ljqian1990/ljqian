<?php
namespace Pressure\Classes;

use Closure;
use Pressure\Callback\Base as CallbackBase;
use Pressure\Interfaces\Client as ClientInterface;
use Swoole\IFace\Protocol;

abstract class Client implements ClientInterface
{

    protected $cli = null;
    
    protected $oCallback = null;

    protected $ip = '';

    protected $port = 0;

    protected $timeout = 1;

    public function __construct(Parse $oParse, CallbackBase $oCallback)
    {
        $this->setIp($oParse->getIp());
        $this->setPort($oParse->getPort());
        
        $this->setOcallback($oCallback);
        return $this;
    }

    abstract public function send();

    protected function setIp(String $ip)
    {
        $this->ip = $ip;
    }

    protected function setPort(int $port)
    {
        $this->port = $port;
    }

    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }
    
    protected function setOcallback(CallbackBase $oCallback)
    {
        $this->oCallback = $oCallback;
    }

    protected function getIp()
    {
        return $this->ip;
    }

    protected function getPort()
    {
        return $this->port;
    }

    protected function getTimeout()
    {
        return $this->timeout;
    }
    
    protected function getOcallback()
    {
        return $this->oCallback;
    }
}