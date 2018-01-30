<?php
namespace Pressure\Callback;

use Pressure\Classes\Client;

class HttpCB extends Base
{

    public function callback(Client $client, $result)
    {
        if ($result == $client->getValue()) {
            echo "OK\r\n";
        } else {
            echo "Error\r\n";
        }
    }
}