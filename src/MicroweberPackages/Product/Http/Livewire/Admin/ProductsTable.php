<?php

namespace MicroweberPackages\Product\Http\Livewire\Admin;

use Illuminate\Database\Eloquent\Builder;
use MicroweberPackages\Admin\AdminDataTableComponent;
use MicroweberPackages\Livewire\Views\Columns\MwCardColumn;
use MicroweberPackages\Livewire\Views\Filters\PriceRangeFilter;
use MicroweberPackages\Product\Models\Product;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class ProductsTable extends AdminDataTableComponent
{
    protected $model = Product::class;
    public array $perPageAccepted = [1, 25, 50, 100, 200];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            //->setDebugEnabled()
            ->setReorderEnabled()
            ->setSortingEnabled()
            ->setSearchEnabled()
            ->setSearchDebounce(0)
            ->setDefaultReorderSort('position', 'asc')
            ->setReorderMethod('changePosition')
            ->setFilterLayoutSlideDown()
            ->setRememberColumnSelectionDisabled()
            ->setUseHeaderAsFooterEnabled()
            ->setHideBulkActionsWhenEmptyEnabled();
    }

    public function columns(): array
    {
        return [
            MwCardColumn::make('Card', 'id')
                ->icon('mdi mdi-shopping mdi-18px')
                ->noThumbnailIcon('mdi mdi-shopping mdi-48px text-muted text-opacity-5')
                ->buttons(function ($row) {
                    $buttons = [
                         [
                            'name'=>'Edit',
                            'class'=>'btn btn-outline-primary btn-sm',
                            'href'=>route('admin.page.edit', $row->id),
                         ],
                        [
                            'name'=>'Live edit',
                            'class'=>'btn btn-outline-success btn-sm',
                            'href'=>route('admin.page.edit', $row->id),
                         ],
                        [
                            'name'=>'Delete',
                            'class'=>'btn btn-outline-danger btn-sm',
                            'href'=>route('admin.page.edit', $row->id),
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
                })
                ->attributes(function($row) {
                return [''];
            })->sortable(),
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
        $query->orderBy('position','asc');

        if ($this->hasSearch()) {
            $search = $this->getSearch();
            $search = trim(strtolower($search));

            $query->where(function (Builder $subQuery) use ($search) {
                $subQuery->whereRaw('LOWER(`title`) LIKE ? ', ['%' . $search . '%']);
                $subQuery->orWhereRaw('LOWER(`url`) LIKE ? ', ['%' . $search . '%']);
            });
        }

        return $query;
    }

    public function filters(): array
    {
        return [

         /*   PriceRangeFilter::make('Price range')
             ->config([
                 'placeholder' => 'Select price range',
             ])
             ->filter(function(Builder $builder, string $value) {
                 $builder->where('id', 'like', '%'.$value.'%');
             }),*/

            SelectFilter::make('Visible')
                ->setFilterPillTitle('Visible')
                ->options([
                    '' => 'Any',
                    '1' => 'Published',
                    '0' => 'Unpublished',
                ])
                ->filter(function(Builder $builder, string $value) {
                    if ($value === '1') {
                        $builder->where('is_active', 1);
                    } elseif ($value === '0') {
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

}

