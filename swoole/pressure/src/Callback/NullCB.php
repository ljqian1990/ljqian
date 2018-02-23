<?php
namespace Pressure\Callback;

use Pressure\Clients\Client;

class NullCB extends Base
{
    public function callback(Client $client, $result)
    {
        
    }
}