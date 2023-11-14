<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6353d621dc9b71e0e1b90530d0c9d723
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6353d621dc9b71e0e1b90530d0c9d723::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6353d621dc9b71e0e1b90530d0c9d723::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit6353d621dc9b71e0e1b90530d0c9d723::$classMap;

        }, null, ClassLoader::class);
    }
}