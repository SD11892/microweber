<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Order\Models\Order;
use MicroweberPackages\Page\Models\Page;

class OrdersTableComponent extends Component
{
    use WithPagination;

    public $paginate = 10;
    protected $paginationTheme = 'bootstrap';

    public $filters = [];
    public $appliedFilters = [];
    public $showColumns = [];

    protected $queryString = ['page'];

    protected $listeners = [
        'refreshOrdersFilters' => '$refresh',
        'setFiltersToOrders' => 'setFilters',
        'setFirstPageToOrders' => 'setFirstPagePagination',
    ];

    public function setFilters($data)
    {
        if (isset($data['filters'])) {
            $this->filters = $data['filters'];
        }

        if (isset($data['showColumns'])) {
            $this->showColumns = $data['showColumns'];
        }

        if (isset($data['page'])) {
            $this->setPage($data['page']);
        }

        $this->emitSelf('$refresh');
    }

    public function render()
    {
        return view('order::admin.orders.livewire.table', [
            'orders' => $this->orders,
            'showColumns' => $this->showColumns,
        ]);
    }

    public function getOrdersProperty()
    {
        return $this->ordersQuery->paginate($this->paginate);
    }

    public function getOrdersQueryProperty()
    {
        $query = Order::query();

        $whitelistedEmptyKeys = ['isPaid'];
        foreach ($this->filters as $filterKey => $filterValue) {

            if (!in_array($filterKey, $whitelistedEmptyKeys)) {
                if (empty($filterValue)) {
                    continue;
                }
            }

            $this->appliedFilters[$filterKey] = $filterValue;
        }

        $query->filter($this->appliedFilters);

        return $query;
    }
}

