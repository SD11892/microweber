<?php

namespace MicroweberPackages\Product\Http\Livewire\Admin;

use Illuminate\Database\Eloquent\Builder;
use MicroweberPackages\Admin\AdminDataTableComponent;
use MicroweberPackages\CustomField\Fields\Number;
use MicroweberPackages\Livewire\Views\Columns\HtmlColumn;
use MicroweberPackages\Livewire\Views\Columns\MwCardColumn;
use MicroweberPackages\Livewire\Views\Columns\MwCardTitleCategoriesButtonsColumn;
use MicroweberPackages\Livewire\Views\Filters\CategoryFilter;
use MicroweberPackages\Livewire\Views\Filters\NumberWithOperator;
use MicroweberPackages\Livewire\Views\Filters\PriceRangeFilter;
use MicroweberPackages\Livewire\Views\Filters\HiddenFilter;
use MicroweberPackages\Livewire\Views\Filters\TagsFilter;
use MicroweberPackages\Livewire\Views\Filters\MwMultiSelectFilter;
use MicroweberPackages\Product\Models\Product;
use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\NumberFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;

class ProductsTable extends AdminDataTableComponent
{
    protected $model = Product::class;
  //  public array $perPageAccepted = [1, 25, 50, 100, 200];

    protected $listeners = ['refreshProductsTable' => '$refresh'];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setReorderEnabled()
            ->setSortingEnabled()
            ->setSearchEnabled()
            ->setDefaultReorderSort('position', 'asc')
            ->setReorderMethod('changePosition')
            ->setFilterLayoutSlideDown()
            ->setColumnSelectDisabled()
            ->setUseHeaderAsFooterEnabled()
            ->setBulkActionsEnabled()
            ->setHideBulkActionsWhenEmptyEnabled();
    }

    public function columns(): array
    {
        return [

         ImageColumn::make('Image')
             ->location(function($row) {
                 return $row->thumbnail();
             })
             ->attributes(function($row) {
                 return [
                     'class' => 'w-8 h-8 rounded-full',
                 ];
             }),

            MwCardTitleCategoriesButtonsColumn::make('Title')
                ->buttons(function ($row) {
                $buttons = [
                    [
                        'name'=>'Edit',
                        'class'=>'btn btn-outline-primary btn-sm',
                        'href'=>route('admin.product.edit', $row->id),
                    ],
                    [
                        'name'=>'Live edit',
                        'class'=>'btn btn-outline-success btn-sm',
                        'href'=>route('admin.product.edit', $row->id),
                    ],
                    [
                        'name'=>'Delete',
                        'class'=>'btn btn-outline-danger btn-sm',
                        'href'=>route('admin.product.edit', $row->id),
                    ],
                ];

                if ($row->is_active < 1) {
                    $buttons[] = [
                        'name'=>'Unpublished',
                        'class'=>'badge badge-warning font-weight-normal',
                        'href'=> "",
                    ];
                }

                return $buttons;
            }),

            HtmlColumn::make('Price' , 'price')
            ->sortable()
            ->setOutputHtml(function($row) {
                if ($row->hasSpecialPrice()) {
                    $price = '<span class="h6" style="text-decoration: line-through;">'.currency_format($row->price).'</span>';
                    $price .= '<br /><span class="h5">'.currency_format($row->specialPrice).'</span>';
                } else {
                    $price = '<span class="h5">'.currency_format($row->price).'</span>';
                }
                return $price;
            }),

            HtmlColumn::make('Stock','content.InStock')
            ->setOutputHtml(function($row) {
                if ($row->InStock) {
                    $stock = '<span class="badge badge-success badge-sm">In stock</span>';
                } else {
                    $stock = '<span class="badge badge-danger badge-sm">Out Of Stock</span>';
                }
                return $stock;
            }),

            HtmlColumn::make('Sales', 'sales')
            ->sortable()
            ->setOutputHtml(function($row) {
                $ordersUrl = route('admin.order.index') . '?productId='.$row->id;
                if ($row->salesCount == 1) {
                    $sales = '<a href="'.$ordersUrl.'"><span class="text-green">'.$row->salesCount.' sale</span></a>';
                } else if ($row->salesCount > 1) {
                    $sales = '<a href="'.$ordersUrl.'"><span class="text-green">'.$row->salesCount.' sales</span></a>';
                } else {
                    $sales = '<span>'.$row->salesCount.' sales</span>';
                }
                return $sales;
            }),

            HtmlColumn::make('Quantity','quantity')
            ->sortable()
            ->setOutputHtml(function($row) {
                if ($row->qty == 'nolimit') {
                    $quantity = '<i class="fa fa-infinity" title="Unlimited Quantity"></i>';
                } else if ($row->qty == 0) {
                    $quantity = '<span class="text-small text-danger">0</span>';
                } else {
                    $quantity = $row->qty;
                }
                return $quantity;
            }),

        ];
    }

    public function changePosition($items): void
    {
        foreach ($items as $item) {
            Product::find((int)$item['value'])->update(['position' => (int)$item['order']]);
        }
    }

    public function builder(): Builder
    {
        $query = Product::query();
        $query->select(['content.id','content.is_active','content.title','content.url','content.position','content.created_by']);

        $filters = [];

        $sortSalesDirection = $this->getSort('sales');
        if ($sortSalesDirection) {
            $filters['sortSales'] = $sortSalesDirection;
        }

        $sortPriceDirection = $this->getSort('price');
        if ($sortPriceDirection) {
            $filters['sortPrice'] = $sortPriceDirection;
        }

        $priceRange = $this->getAppliedFilterWithValue('price_range');
        if ($priceRange) {
            if (strpos($priceRange, ',') !== false) {
                $filters['priceBetween'] = $priceRange;
            }
        }

        $sales = $this->getAppliedFilterWithValue('sales');
        if ($sales) {
            $filters['sales'] = $sales;
        }

        $salesOperator = $this->getAppliedFilterWithValue('sales_operator');
        if ($salesOperator) {
            $filters['salesOperator'] = $salesOperator;
        }

        $quantity = $this->getAppliedFilterWithValue('quantity');
        if ($quantity) {
            $filters['qty'] = $quantity;
        }

        $stockStatus = $this->getAppliedFilterWithValue('stock_status');
        if ($stockStatus == 'in_stock') {
            $filters['inStock'] = true;
        }
        if ($stockStatus == 'out_of_stock') {
            $filters['inStock'] = false;
        }

        $sku = $this->getAppliedFilterWithValue('s_k_u');
        if (!empty($sku)) {
            $filters['contentData']['sku'] = $sku;
        }

        $discount = $this->getAppliedFilterWithValue('discount');
        if ($discount == 'discounted') {
            $filters['discounted'] = true;
        }
        if ($discount == 'not_discounted') {
            $filters['notDiscounted'] = true;
        }

        if ($this->hasSearch()) {
            $search = $this->getSearch();
            $filters['title'] = $search;
        }

        $query->filter($filters);

        return $query;
    }

    public function filters(): array
    {
        return [

            MwMultiSelectFilter::make('Multiselect Filter')
                ->config([
                    'class'=> 'col-12 col-sm-6 col-md-3 col-lg-2 mb-4',
                ])
                ->setFilterPillTitle('Multiselect Filter')
                ->options([
                    '' => 'Any',
                    'in_stock' => 'In Stock',
                    'out_of_stock' => 'Out Of Stock',
                ])
                ->filter(function(Builder $builder, $values) {


                }),

            HiddenFilter::make('Page')->hiddenFromAll(),

            CategoryFilter::make('Category')
                ->config([
                    'class'=> 'col-12 col-sm-6 col-md-3 col-lg-3 mb-4',
                    'placeholder' => 'Select category',
                ])->filter(function(Builder $builder, string $value) {

                }),

            TagsFilter::make('Tags')
                ->config([
                    'class'=> 'col-12 col-sm-6 col-md-3 col-lg-3 mb-4',
                    'placeholder' => 'Select tags',
                ])->filter(function(Builder $builder, string $value) {

                }),

            PriceRangeFilter::make('Price range')
             ->config([
                 'class'=> 'col-12 col-sm-6 col-md-4 col-lg-5 mb-4',
                 'placeholder' => 'Select price range',
             ])->filter(function(Builder $builder, string $value) {

             }),

            HiddenFilter::make('Sales Operator')->hiddenFromAll(),

            NumberWithOperator::make('Sales')
                ->config([
                    'class'=> 'col-12 col-sm-6 col-md-3 col-lg-4 mb-4',
                ])
                ->filter(function(Builder $builder, $values) {
                    //
                }),

            /*   NumberFilter::make('Sales')
                   ->config([
                       'class'=> 'col-12 col-sm-6 col-md-4 col-lg-4 mb-4',
                   ])
                   ->filter(function(Builder $builder, string $value) {
                       //
                   }),*/

            SelectFilter::make('Stock Status')
                ->config([
                    'class'=> 'col-12 col-sm-6 col-md-3 col-lg-2 mb-4',
                ])
                ->setFilterPillTitle('Stock Status')
                ->options([
                    '' => 'Any',
                    'in_stock' => 'In Stock',
                    'out_of_stock' => 'Out Of Stock',
                ])
                ->filter(function(Builder $builder, string $value) {


                }),

            SelectFilter::make('Discount')
                ->config([
                    'class'=> 'col-12 col-sm-6 col-md-3 col-lg-2 mb-4',
                ])
                ->setFilterPillTitle('Type')
                ->options([
                    '' => 'Any',
                    'discounted' => 'Discounted',
                    'not_discounted' => 'Not discounted',
                ])
                ->filter(function(Builder $builder, string $value) {


                }),

            NumberFilter::make('Quantity')
                ->config([
                    'class'=> 'col-12 col-sm-6 col-md-3 col-lg-2 mb-4',
                ])
                ->filter(function(Builder $builder, string $value) {
                    //
                }),

            TextFilter::make('SKU')
                ->config([

                ])
                ->filter(function(Builder $builder, string $value) {
                    //
                }),

            SelectFilter::make('Visible')
                ->setFilterPillTitle('Visible')
                ->options([
                    '' => 'Any',
                    'published' => 'Published',
                    'unpublished' => 'Unpublished',
                ])
                ->filter(function(Builder $builder, string $value) {
                    if ($value === 'published') {
                        $builder->where('is_active', 1);
                    } elseif ($value === 'unpublished') {
                        $builder->where('is_active', 0);
                    }
                }),

            DateFilter::make('Created at')
                ->filter(function(Builder $builder, string $value) {
                    $builder->where('created_at', '>=', $value);
                }),

            DateFilter::make('Updated at')
                ->filter(function(Builder $builder, string $value) {
                    $builder->where('updated_at', '>=', $value);
                })
        ];
    }

    public function multipleMoveToCategory()
    {
        $selected = $this->getSelected();
        $this->emit('multipleMoveToCategory',$selected );
    }

    public function multiplePublish()
    {
        $selected = $this->getSelected();
        $this->emit('multiplePublish',$selected );
    }

    public function multipleUnpublish()
    {
        $selected = $this->getSelected();
        $this->emit('multipleUnpublish',$selected );
    }

    public function multipleDelete()
    {
        $selected = $this->getSelected();
        $this->emit('multipleDelete',$selected );
    }

    public function bulkActions(): array
    {
        $bulkActions = [
            'multipleMoveToCategory' => 'Move to category',
            'multiplePublish' => 'Publish',
            'multipleUnpublish' => 'Unpublish',
            'multipleDelete' => 'Delete',
        ];

        return $bulkActions;
    }
}

