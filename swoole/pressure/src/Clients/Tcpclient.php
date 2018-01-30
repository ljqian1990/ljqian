<?php
namespace Pressure\Clients;

use Pressure\Callback\Base as CallbackBase;
use Pressure\Clients\Client as ClientBase;
use Pressure\Libraries\Parse;
use Closure;
use swoole_client;

class Tcpclient extends ClientBase
{   
    private $sendata = '';

    public function __construct(Parse $oParse, CallbackBase $oCallback)
    {
        parent::__construct($oParse, $oCallback);
        
        $this->setSendata($oParse->getSendata());
        
        return $this;
    }

    public function send()
    {
        $this->cli = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
        
        $this->cli->on("connect", function(swoole_client $cli) {
            $cli->send($this->getSendata());
        });
        $this->cli->on("receive", function(swoole_client $cli, $data){            
            $cli->result = $data;
            $this->getOcallback()->callback($this, $cli);
            $cli->close();
        });
        $this->cli->on("error", function(swoole_client $cli){
            echo "errCode:{$cli->errCode}\n";
        });
        $this->cli->on("close", function(swoole_client $cli){});
        $this->cli->connect($this->getIp(), $this->getPort(), $this->getTimeout());        
    }

    private function setSendata(String $sendata)
    {
        $this->sendata = $sendata;
        return $this;
    }

    private function getSendata()
    {
        return $this->sendata;
    }
}