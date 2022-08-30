<?php

namespace MicroweberPackages\Page\Http\Livewire\Admin;

use Illuminate\Database\Eloquent\Builder;
use MicroweberPackages\Admin\AdminDataTableComponent;
use MicroweberPackages\Livewire\Views\Columns\MwCardColumn;
use MicroweberPackages\Page\Models\Page;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PagesTable extends AdminDataTableComponent
{
    protected $model = Page::class;
    public array $perPageAccepted = [10, 25, 50, 100, 200];

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
            ->setColumnSelectDisabled()
            ->setUseHeaderAsFooterEnabled()
            ->setHideBulkActionsWhenEmptyEnabled();
    }

    public function columns(): array
    {
        return [
            MwCardColumn::make('Card', 'id')
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
            Page::find((int)$item['value'])->update(['position' => (int)$item['order']]);
        }
    }

    public function builder(): Builder
    {
        $query = Page::query();
        $query->select(['content.id','content.title','content.url','content.position','content.created_by']);
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
}

