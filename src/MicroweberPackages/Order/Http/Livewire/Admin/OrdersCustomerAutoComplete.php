<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin;

use Livewire\Component;
use MicroweberPackages\Order\Models\Order;

class OrdersCustomerAutoComplete extends Component
{
    public $query;
    public $data;
    public $selectedId;

    public function mount()
    {
        $this->resetProperties();
    }

    public function render()
    {
        return view('order::admin.orders.livewire.customer-auto-complete');
    }

    public function resetProperties()
    {
        $this->query = '';
        $this->data = [];
    }

    public function selectId(int $id)
    {
        $getOrder = Order::where('id', $id)->first();
        if ($getOrder != null) {

            $this->query = $getOrder->first_name . $getOrder->last_name;

            $this->selectedId = $id;
            $this->refreshQueryData();
            $this->emitSelf('$refresh');
        }
    }

    public function updatedQuery()
    {
        $this->selectedId = false;
        $this->refreshQueryData();
    }

    public function refreshQueryData()
    {
        $query = Order::query();

        $keyword = trim($this->query);

        if (!empty($keyword)) {
            $query->where('first_name', 'like', '%' . $keyword . '%');
            $query->orWhere('last_name', 'like', '%' . $keyword . '%');
            $query->orWhere('email', 'like', '%' . $keyword . '%');
        }

        if ($this->selectedId > 0) {
            $query->where('id', $this->selectedId);
        }

        $query->limit(10);

        $get = $query->get();

        if ($get != null) {
            $this->data = $get->toArray();
        }
    }
}
