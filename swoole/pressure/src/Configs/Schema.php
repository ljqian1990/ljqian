<?php
namespace Pressure\Configs;

class Schema
{
    public function supportList()
    {
        return ['http', 'redis', 'tcp', 'websocket', 'udp', 'mysql'];
    }
}