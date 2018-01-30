<?php
namespace Pressure\Interfaces;

use Closure;
use Pressure\Classes\Client;

interface CallbackInterface {
    public function callback(Client $client, $result);
}