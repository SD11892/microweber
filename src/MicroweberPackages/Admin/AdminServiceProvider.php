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

namespace MicroweberPackages\Admin;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Admin\Http\Livewire\AutoCompleteMultipleSelectComponent;
use MicroweberPackages\Admin\Http\Livewire\DropdownComponent;
use MicroweberPackages\Admin\Http\Livewire\FilterItemCateogry;
use MicroweberPackages\Admin\Http\Livewire\FilterItemComponent;
use MicroweberPackages\Admin\Http\Livewire\FilterItemDate;
use MicroweberPackages\Admin\Http\Livewire\FilterItemMultipleSelectComponent;
use MicroweberPackages\Admin\Http\Livewire\FilterItemTags;
use MicroweberPackages\Admin\Http\Livewire\FilterItemUsers;
use MicroweberPackages\Admin\Http\Livewire\FilterItemValueRange;
use MicroweberPackages\Admin\Http\Livewire\FilterItemValueWithOperator;
use MicroweberPackages\Admin\Http\Livewire\FilterItemValue;
use MicroweberPackages\Admin\Http\Livewire\ProductsAutoComplete;
use MicroweberPackages\Admin\Http\Livewire\TagsAutoComplete;
use MicroweberPackages\Admin\Http\Livewire\UsersAutoComplete;
use MicroweberPackages\Livewire\Http\Livewire\Admin\AutoCompleteComponent;

class AdminServiceProvider extends ServiceProvider
{
    public function register()
    {
        View::addNamespace('admin', __DIR__.'/resources/views');
        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');
    }

    public function boot()
    {
       // Livewire::component('admin-auto-complete', AutoCompleteComponent::class);
       //  Livewire::component('admin-auto-complete-multiple-items', AutoCompleteMultipleItemsComponent::class);
        Livewire::component('admin-dropdown', DropdownComponent::class);
        Livewire::component('admin-users-autocomplete', UsersAutoComplete::class);
        Livewire::component('admin-tags-autocomplete', TagsAutoComplete::class);

        Livewire::component('admin-filter-item', FilterItemComponent::class);
        Livewire::component('admin-filter-item-category', FilterItemCateogry::class);
        Livewire::component('admin-filter-item-multiple-items', FilterItemMultipleSelectComponent::class);
        Livewire::component('admin-filter-item-users', FilterItemUsers::class);
        Livewire::component('admin-filter-item-tags', FilterItemTags::class);
        Livewire::component('admin-filter-item-date', FilterItemDate::class);
        Livewire::component('admin-filter-item-value-with-operator', FilterItemValueWithOperator::class);
        Livewire::component('admin-filter-item-value', FilterItemValue::class);
        Livewire::component('admin-filter-item-value-range', FilterItemValueRange::class);

    }
}
