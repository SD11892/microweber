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

namespace MicroweberPackages\Category\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Category\CategoryManager;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Category\Models\CategoryItem;
use MicroweberPackages\Database\Observers\BaseModelObserver;

class CategoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\Category\Category    $category_manager
         */
        $this->app->singleton('category_manager', function ($app) {
            return new CategoryManager();
        });

        Category::observe(BaseModelObserver::class);
        CategoryItem::observe(BaseModelObserver::class);

        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
    }
}

