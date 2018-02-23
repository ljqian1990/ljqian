<?php
namespace Pressure\Callback;

use Pressure\Clients\Client;

class UdpCB extends Base
{

    public function callback(Client $client, $result)
    {
        if ($result->errCode == 0) {           
            echo "OK\r\n";
        } else {
            echo "ErrCode:{$result->errCode}\r\n";
        }
    }
}