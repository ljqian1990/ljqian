<?php
namespace Pressure\Callback;

use Pressure\Clients\Client;

class MysqlCB extends Base
{

    public function callback(Client $client, $result)
    {
        if ($result->errno == 0) {           
            echo "OK\r\n";
        } else {
            echo "connectted error.errno:{$db->connect_errno}, errmsg:{$db->connect_error}\r\n";            
        }
    }
}