<?php
namespace Pressure\Classes;

use Closure;
use Pressure\Interfaces\Client as ClientInterface;

abstract class Client implements ClientInterface
{

    protected $cli = null;

    protected $ip = '';

    protected $port = 0;

    protected $timeout = 1;

    public function __construct(Parse $oParse)
    {
        $this->setIp($oParse->getIp());
        $this->setPort($oParse->getPort());
        return $this;
    }

    abstract public function send(Closure $callback);

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
}