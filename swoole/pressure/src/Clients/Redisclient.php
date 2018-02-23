<?php
namespace Pressure\Clients;

use Pressure\Callback\Base as CallbackBase;
use Pressure\Clients\Client as ClientBase;
use Pressure\Libraries\Parse;
use Closure;
use swoole_redis;

class Redisclient extends ClientBase
{

    private $password = '';

    private $database = 0;

    private $key = '';

    private $value = '';

    public function __construct(Parse $oParse, CallbackBase $oCallback)
    {
        parent::__construct($oParse, $oCallback);
        
        $this->setPassword($oParse->getPassword());
        $this->setDatabase($oParse->getDatabase());
        $this->setKey($oParse->getKey());
        $this->setValue($oParse->getValue());
        
        return $this;
    }

    public function send()
    {
        $options = [
            'password' => $this->getPassword(),
            'database' => $this->getDatabase(),
            'timeout' => $this->getTimeout()
        ];
        
        $this->cli = new swoole_redis();
        $this->cli->connect($this->getIp(), $this->getPort(), function (swoole_redis $client, $result) {
            $client->set($this->getKey(), $this->getValue(), function (swoole_redis $client, $result) {                
                $client->get($this->getKey(), function (swoole_redis $client, $result) {
                    $client->result = $result;
                    $this->getOcallback()->callback($this, $client);   
                    $client->close();
                });
            });
        });
    }

    private function setPassword(String $password)
    {
        $this->password = $password;
        return $this;
    }

    private function setDatabase(int $database)
    {
        $this->database = $database;
        return $this;
    }

    private function setKey(String $key)
    {
        $this->key = $key;
        return $this;
    }

    private function setValue(String $value)
    {
        $this->value = $value;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getValue()
    {
        return $this->value;
    }
}