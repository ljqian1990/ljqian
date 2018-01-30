<?php
namespace Pressure\Interfaces;

use Closure;
use Pressure\Clients\Client;

interface CallbackInterface {
    public function callback(Client $client, $result);
}