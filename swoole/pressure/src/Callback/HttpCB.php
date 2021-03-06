<?php
namespace Pressure\Callback;

use Pressure\Clients\Client;

class HttpCB extends Base
{

    public function callback(Client $client, $result)
    {
        if ($result->statusCode == 200) {           
            echo "OK\r\n";
        } else {
            echo "Error\r\n";
        }
    }
}