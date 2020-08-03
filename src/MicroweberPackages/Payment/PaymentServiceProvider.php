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

namespace MicroweberPackages\Payment;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        View::addNamespace('payment', __DIR__.'/resources/views');

        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/');
    }
}
