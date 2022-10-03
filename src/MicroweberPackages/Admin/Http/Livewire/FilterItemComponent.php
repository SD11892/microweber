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

    public string $view = 'admin::livewire.filters.filter-item';

    public function updatedSelectedItem($value)
    {
        $this->selectItem($value);
    }

    public function refreshQueryData()
    {
        if (!empty($this->data)) {
            foreach ($this->data as $item) {
                if ($item['key'] == $this->selectedItem) {
                    $this->selectedItem = $item['value'];
                }
            }
        }

        $this->total = count($this->data);
    }
}
