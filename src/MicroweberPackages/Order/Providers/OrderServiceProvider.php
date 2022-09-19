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

namespace MicroweberPackages\Order\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Admin\Http\Livewire\UsersAutoComplete;
use MicroweberPackages\Order\Http\Controllers\OrdersController;
use MicroweberPackages\Order\Http\Livewire\Admin\OrdersCustomersAutoComplete;
use MicroweberPackages\Order\Http\Livewire\Admin\OrdersFiltersComponent;
use MicroweberPackages\Order\Http\Livewire\Admin\OrdersTableComponent;
use MicroweberPackages\Order\OrderManager;

class OrderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //Livewire::component('content-bulk-options', ContentBulkOptions::class);
        Livewire::component('admin-orders-filters', OrdersFiltersComponent::class);
        Livewire::component('admin-orders-table', OrdersTableComponent::class);
        Livewire::component('admin-orders-customers-autocomplete', OrdersCustomersAutoComplete::class);

        /**
         * @property \MicroweberPackages\Order    $order_manager
         */
        $this->app->singleton('order_manager', function ($app) {
            return new OrderManager();
        });

        View::addNamespace('order', dirname(__DIR__) . '/resources/views');

        $this->loadRoutesFrom(dirname(__DIR__) . '/routes/web.php');
        $this->loadRoutesFrom(dirname(__DIR__) . '/routes/api.php');


    }
}
