<?php
namespace Pressure\Clients;

use Pressure\Callback\Base as CallbackBase;
use Pressure\Clients\Client as ClientBase;
use Pressure\Libraries\Parse;
use Closure;
use Exception;
use swoole_http_client;
use swoole_websocket_frame;

class Websocketclient extends ClientBase
{
    private $sendata = '';
    private $uri = ''; 

    public function __construct(Parse $oParse, CallbackBase $oCallback)
    {
        parent::__construct($oParse, $oCallback);
        
        $this->setSendata($oParse->getSendata());
        $this->setUri($oParse->getUri());
        
        return $this;
    }

    public function send()
    {
        $this->cli = new swoole_http_client($this->getIp(), $this->getPort());
        
        $this->cli->on('message', function(swoole_http_client $cli, swoole_websocket_frame $frame) {
            $cli->frame = $frame;
            $this->getOcallback()->callback($this, $cli);
            $this->cli->close();
        });
        
        $this->cli->upgrade($this->getUri(), function(swoole_http_client $cli) {
            $cli->push($this->getSendata());
        });        
        
        $this->cli->set([
            'timeout' => $this->getTimeout()
        ]);
    }

    private function setSendata(String $sendata)
    {
        $this->sendata = $sendata;
        return $this;
    }

    private function setUri(String $uri)
    {
        $this->uri = $uri;
        return $this;
    }

    private function getSendata()
    {
        return $this->sendata;
    }

    private function getUri()
    {
        if (empty($this->uri)) {
            throw new Exception('uri不能为空');
        }
        return $this->uri;
    }
}