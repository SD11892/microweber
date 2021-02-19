<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Option\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use MicroweberPackages\Option\Facades\Option as OptionFacade;
use MicroweberPackages\Option\GlobalOptions;
use MicroweberPackages\Option\Models\Option as OptionModel;
use MicroweberPackages\Option\OptionManager;


class OptionServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('option_manager', function ($app) {
            return new OptionManager();
        });

        $this->app->bind('option',function(){
            return new OptionModel();
        });

        $this->app->singleton('global_options', function ($app) {
            return new GlobalOptions(OptionModel::all());
        });

    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(dirname(__DIR__) . '/migrations/');

        $aliasLoader = AliasLoader::getInstance();
        $aliasLoader->alias('Option', OptionFacade::class);

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['option_manager', 'option'];
    }
}
