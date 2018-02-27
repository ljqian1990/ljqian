<?php
namespace Jigsaw\Services;

use Jigsaw\Libraries\Factory as LibFactory;

class Base
{

    protected $config;

    protected $env;

    protected $exception;

    protected $func;

    protected $mcrypt;

    protected $curl;

    public function __construct()
    {
        $this->config = LibFactory::loadConfig();
        $this->env = LibFactory::loadEnv();
        $this->exception = LibFactory::loadException();
        $this->func = LibFactory::loadFunc();
        $this->mcrypt = LibFactory::loadMcrypt();
        $this->curl = LibFactory::loadCurl();
    }
}