<?php
namespace Pressure\Classes;

use Pressure\Classes\Client as ClientBase;
use Closure;
use swoole_redis;

class Redisclient extends ClientBase
{

    private $password = '';

    private $database = 0;

    private $key = '';

    private $value = '';

    public function __construct(Parse $oParse)
    {
        parent::__construct($oParse);
        
        $this->setPassword($oParse->getPassword());
        $this->setDatabase($oParse->getDatabase());
        $this->setKey($oParse->getKey());
        $this->setValue($oParse->getValue());
        
        return $this;
    }

    public function send(Closure $callback)
    {
        $options = [
            'password' => $this->getPassword(),
            'database' => $this->getDatabase(),
            'timeout' => $this->getTimeout()
        ];
        
        $this->cli = new swoole_redis();
        $this->cli->connect($this->getIp(), $this->getPort(), function (swoole_redis $client, $result) use($callback) {
            $client->set($this->getKey(), $this->getValue(), function (swoole_redis $client, $result) use($callback) {                
                $client->get($this->getKey(), function (swoole_redis $client, $result) use($callback) {                    
                    if ($result == $this->getValue()) {
                        echo "OK\r\n";
                    } else {
                        echo "Error\r\n";
                    }
                    $callback($client);
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

    private function getPassword()
    {
        return $this->password;
    }

    private function getDatabase()
    {
        return $this->database;
    }

    private function getKey()
    {
        return $this->key;
    }

    private function getValue()
    {
        return $this->value;
    }
}