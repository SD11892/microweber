<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5a10948c9de948909daeab3650ec270a
{
    public static $prefixesPsr0 = array (
        'R' => 
        array (
            'Recurr' => 
            array (
                0 => __DIR__ . '/../..' . '/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit5a10948c9de948909daeab3650ec270a::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
