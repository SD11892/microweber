<?php

namespace MicroweberPackages\Product;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Database\Observers\BaseModelObserver;
use MicroweberPackages\Product\Http\Livewire\Admin\ContentBulkOptions;
use MicroweberPackages\Product\Http\Livewire\Admin\ProductsIndexComponent;
use MicroweberPackages\Product\Http\Livewire\Admin\ProductsTable;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\Product\Observers\ProductObserver;
use MicroweberPackages\Product\Validators\PriceValidator;
use MicroweberPackages\Utils\Captcha\Validators\CaptchaValidator;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Livewire::component('content-bulk-options', ContentBulkOptions::class);
        Livewire::component('admin-products-table', ProductsTable::class);
        Livewire::component('admin-products-index', ProductsIndexComponent::class);

        Product::observe(BaseModelObserver::class);
      //  Product::observe(ProductObserver::class); ->moved to CustomFieldsTrait

        View::addNamespace('product', __DIR__ . '/resources/views');

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');

        \Validator::extendImplicit('price', PriceValidator::class.'@validate', 'Invalid price value!');
    }

}
