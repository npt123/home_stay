<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0b789012c5703057128443ccbb9baafd
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'Lcobucci\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Lcobucci\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/lcobucci/jwt/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0b789012c5703057128443ccbb9baafd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0b789012c5703057128443ccbb9baafd::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
