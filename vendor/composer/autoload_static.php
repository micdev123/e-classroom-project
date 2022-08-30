<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0dc9c34bb1707faaf83e6c76d4d5fa8f
{
    public static $prefixesPsr0 = array (
        'C' => 
        array (
            'ConvertApi\\' => 
            array (
                0 => __DIR__ . '/..' . '/convertapi/convertapi-php/lib',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit0dc9c34bb1707faaf83e6c76d4d5fa8f::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit0dc9c34bb1707faaf83e6c76d4d5fa8f::$classMap;

        }, null, ClassLoader::class);
    }
}
