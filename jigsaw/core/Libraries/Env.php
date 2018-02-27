<?php
namespace Jigsaw\Libraries;

class Env
{

    private static $envs = [];

    public static function set($key, $value)
    {
        self::$envs[$key] = json_encode($value);
    }

    public static function get($key, $canEmpty = true)
    {
        $value = self::$envs[$key];
        if (! $canEmpty && empty($value)) {
            Exception::throwSystemException(sprintf(Config::error('ENV_EMPTY_ERROR'), $key));
        }
        return json_decode($value, true);
    }
}