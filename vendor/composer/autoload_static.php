<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8696f1af6d29183fd38acd3d1ed61f57
{
    public static $files = array (
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twig\\' => 5,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Polyfill\\Ctype\\' => 23,
        ),
        'A' => 
        array (
            'App\\Model\\' => 10,
            'App\\Controller\\' => 15,
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twig\\' => 
        array (
            0 => __DIR__ . '/..' . '/twig/twig/src',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'App\\Model\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/model',
        ),
        'App\\Controller\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/controller',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8696f1af6d29183fd38acd3d1ed61f57::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8696f1af6d29183fd38acd3d1ed61f57::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit8696f1af6d29183fd38acd3d1ed61f57::$classMap;

        }, null, ClassLoader::class);
    }
}
