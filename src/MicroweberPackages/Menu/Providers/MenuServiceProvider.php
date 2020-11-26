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

namespace MicroweberPackages\Menu\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Menu\MenuManager;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\Menu\MenuManager    $menu_manager
         */
        $this->app->singleton('menu_manager', function ($app) {
            return new MenuManager();
        });

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }
}