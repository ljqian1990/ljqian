<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit711a2c902d74a2ff7744d8d083d674cc
{
    public static $prefixLengthsPsr4 = array (
        'J' => 
        array (
            'Jigsaw\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Jigsaw\\' => 
        array (
            0 => __DIR__ . '/../..' . '/core',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit711a2c902d74a2ff7744d8d083d674cc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit711a2c902d74a2ff7744d8d083d674cc::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}