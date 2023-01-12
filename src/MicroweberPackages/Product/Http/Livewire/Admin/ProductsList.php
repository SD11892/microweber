<?php

namespace MicroweberPackages\Product\Http\Livewire\Admin;

use MicroweberPackages\Content\Http\Livewire\Admin\ContentList;
use MicroweberPackages\Product\Models\Product;

class ProductsList extends ContentList
{
    public $model = Product::class;

    public $whitelistedEmptyKeys = ['inStock', 'orders', 'qty'];

    public $showColumns = [
        'id' => true,
        'image' => true,
        'title' => true,
        'price' => true,
        'stock' => true,
        'orders' => true,
        'quantity' => true,
        'author' => false
    ];

    public function getDropdownFiltersProperty()
    {
        $dropdownFilters = [];

        $dropdownFilters[] = [
            'name' => 'Taxonomy',
            'type' => 'separator'
        ];

        $dropdownFilters[] = [
            'name' => 'Tags',
            'key' => 'tags'
        ];

        $dropdownFilters[] = [
            'name' => 'Shop',
            'type' => 'separator'
        ];

        $dropdownFilters[] = [
            'name' => 'Price Range',
            'key' => 'priceBetween',
        ];

        $dropdownFilters[] = [
            'name' => 'Stock Status',
            'key' => 'stockStatus',
        ];

        $dropdownFilters[] = [
            'name' => 'Discount',
            'key' => 'discount',
        ];

        $dropdownFilters[] = [
            'name' => 'Orders',
            'key' => 'orders',
        ];

        $dropdownFilters[] = [
            'name' => 'Quantity',
            'key' => 'qty',
        ];

        $dropdownFilters[] = [
            'name' => 'Sku',
            'key' => 'sku',
        ];

        $templateFields = mw()->template->get_data_fields('product');
        if (!empty($templateFields)) {
            $dropdownFilters[] = [
                'name' => 'Template settings',
                'type' => 'separator'
            ];
            foreach ($templateFields as $templateFieldKey => $templateFieldName) {
                $dropdownFilters[] = [
                    'name' => $templateFieldName,
                    'key' => 'contentData.' . $templateFieldKey,
                ];
            }
        }

        $templateFields = mw()->template->get_edit_fields('product');
        if (!empty($templateFields)) {
            $dropdownFilters[] = [
                'name' => 'Template fields',
                'type' => 'separator'
            ];
            foreach ($templateFields as $templateFieldKey => $templateFieldName) {
                $dropdownFilters[] = [
                    'name' => $templateFieldName,
                    'key' => 'contentFields.' . $templateFieldKey,
                ];
            }
        }

        $dropdownFilters[] = [
            'name' => 'Other',
            'type' => 'separator'
        ];

        $dropdownFilters[] = [
            'name' => 'Visible',
            'key' => 'visible',
        ];
        $dropdownFilters[] = [
            'name' => 'Author',
            'key' => 'userId',
        ];

        $dropdownFilters[] = [
            'name' => 'Date Range',
            'key' => 'dateBetween',
        ];

        $dropdownFilters[] = [
            'name' => 'Created at',
            'key' => 'createdAt',
        ];

        $dropdownFilters[] = [
            'name' => 'Updated at',
            'key' => 'updatedAt',
        ];

        return $dropdownFilters;
    }

}

