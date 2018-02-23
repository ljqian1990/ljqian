<?php
namespace Pressure\Callback;

use Pressure\Clients\Client;

class RedisCB extends Base
{

    public function callback(Client $client, $result)
    {
        if ($result->result == $client->getValue()) {
            echo "OK\r\n";
        } else {
            echo "Error\r\n";
        }
    }
}