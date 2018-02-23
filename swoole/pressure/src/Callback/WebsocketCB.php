<?php
namespace Pressure\Callback;

use Pressure\Clients\Client;

class WebsocketCB extends Base
{

    public function callback(Client $client, $result)
    {
        if ($result->statusCode == 101) {
            echo "OK\r\n";
        } else {
            echo "Error\r\n";
        }
    }
}