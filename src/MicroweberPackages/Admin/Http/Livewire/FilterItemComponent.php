<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Tag\Model\Tag;
use MicroweberPackages\User\Models\User;

class FilterItemComponent extends AutoCompleteComponent
{
    public $name = 'Component';
    public $perPage = 10;
    public $total = 0;
    public $searchable = true;
    public $onChangedEmitEvents = [];

    public string $view = 'admin::livewire.filters.filter-item';

    public function hideFilterItem($id)
    {
        if ($this->id == $id) {
            $this->emit('hideFilterItem', $this->selectedItemKey);
            $this->resetProperties();
        }
    }

    public function updatedSelectedItem($value)
    {
        $this->showDropdown($this->id);
        $this->selectItem($value);

        if (!empty($this->onChangedEmitEvents)) {
            foreach($this->onChangedEmitEvents as $event) {
                $this->emit($event);
            }
        }
    }

    public function refreshQueryData()
    {
        if (!empty($this->data)) {
            foreach ($this->data as $item) {
                if ($item['key'] == $this->selectedItem) {
                    $this->selectedItemText = $item['value'];
                }
            }
        }

        if (is_array($this->data)) {
            $this->total = count($this->data);
        } else {
            $this->total = 0;
        }
    }
}
