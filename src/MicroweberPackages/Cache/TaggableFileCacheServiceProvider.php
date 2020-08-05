<?php

namespace MicroweberPackages\Cache;

use Illuminate\Support\ServiceProvider;

class TaggableFileCacheServiceProvider extends ServiceProvider
{
    public function register()
    {
        \Cache::extend('file', function ($app, $config) {

            $locale = app()->getLocale();
            if ($locale) {
                $folder = app()->environment() . '-' . $locale . DIRECTORY_SEPARATOR;
            } else {
                $folder = app()->environment() . DIRECTORY_SEPARATOR;
            }

            $configPath = $config['path'] . DIRECTORY_SEPARATOR . $folder;

            return \Cache::repository(new TaggableFileStore($this->app['files'], $configPath, $config));
        });

    }
}
