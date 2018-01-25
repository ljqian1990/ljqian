<?php
namespace Pressure\Interfaces;

use Closure;

interface Client {
    public function send(Closure $callback);        
}