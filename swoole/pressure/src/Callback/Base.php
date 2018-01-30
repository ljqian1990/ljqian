<?php
namespace Pressure\Callback;

use Pressure\Classes\Client;
use Pressure\Interfaces\CallbackInterface;

abstract class Base implements CallbackInterface
{
    abstract public function callback(Client $client, $result);
}