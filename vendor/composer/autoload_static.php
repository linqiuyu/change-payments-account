<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3a6ab5d4721939299fa014e6d8172606
{
    public static $files = array (
        '007ec23180e39142f832100dc3bef2cb' => __DIR__ . '/../..' . '/includes/helpers.php',
        'f8b452476a196f678435d6c380380571' => __DIR__ . '/..' . '/htmlburger/carbon-fields/core/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Container\\' => 14,
        ),
        'C' => 
        array (
            'Carbon_Fields\\' => 14,
            'CPY\\Processors\\' => 15,
            'CPY\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
        'Carbon_Fields\\' => 
        array (
            0 => __DIR__ . '/..' . '/htmlburger/carbon-fields/core',
        ),
        'CPY\\Processors\\' => 
        array (
            0 => __DIR__ . '/../..' . '/processors',
        ),
        'CPY\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Pimple' => 
            array (
                0 => __DIR__ . '/..' . '/pimple/pimple/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3a6ab5d4721939299fa014e6d8172606::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3a6ab5d4721939299fa014e6d8172606::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit3a6ab5d4721939299fa014e6d8172606::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit3a6ab5d4721939299fa014e6d8172606::$classMap;

        }, null, ClassLoader::class);
    }
}
