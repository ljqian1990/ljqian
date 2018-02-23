<?php
namespace Pressure\Clients;

use Pressure\Callback\Base as CallbackBase;
use Pressure\Clients\Client as ClientBase;
use Pressure\Libraries\Parse;
use Closure;
use Exception;
use swoole_mysql;

class Mysqlclient extends ClientBase
{
    
    private $user = '';
    private $password = '';
    private $database = '';
    private $charset = 'utf8';
    private $sql = '';
    
    public function __construct(Parse $oParse, CallbackBase $oCallback)
    {
        parent::__construct($oParse, $oCallback);
        
        $this->setUser($oParse->getUser());
        $this->setPassword($oParse->getPassword());
        $this->setDatabase($oParse->getDatabase());
        $this->setCharset($oParse->getCharset());
        $this->setSql($oParse->getSql());
        
        return $this;
    }

    public function send()
    {
        $this->cli = new swoole_mysql;
        $server = [
            'host' => $this->getIp(),
            'port' => $this->getPort(),
            'user' => $this->getUser(),
            'password' => $this->getPassword(),
            'database' => $this->getDatabase(),
            'charset' => $this->getCharset(),
            'timeout' => $this->getTimeout()
        ];
        
        $this->cli->connect($server, function(swoole_mysql $db, $r) {
            if ($r === false) {
                echo "connectted error.errno:{$db->connect_errno}, errmsg:{$db->connect_error}\r\n";
            }
            $db->query($this->getSql(), function (swoole_mysql $db, $r) {
                $db->result = $r;
                $this->getOcallback()->callback($this, $db);
                $db->close();
            });
        });        
    }

    private function setUser(String $user)
    {
        $this->user = $user;
        return $this;
    }

    private function setPassword(String $password)
    {
        $this->password = $password;
        return $this;
    }

    private function setDatabase(String $database)
    {
        $this->database = $database;
        return $this;
    }

    private function setCharset(String $charset)
    {
        $this->charset = $charset;
        return $this;
    }
    
    private function setSql(String $sql)
    {
        $this->sql = $sql;
        return $this;
    }

    private function getUser()
    {
        return $this->user;
    }

    private function getPassword()
    {
        return $this->password;
    }

    private function getDatabase()
    {
        return $this->database;
    }

    private function getCharset()
    {
        return $this->charset;
    }
    
    private function getSql()
    {
        return $this->sql;
    }
}