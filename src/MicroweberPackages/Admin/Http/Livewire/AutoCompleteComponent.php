<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Livewire\Component;

class AutoCompleteComponent extends Component
{
    public $model;
    public $selectedItem;
    public $selectedItemKey = 'auto_complete_id';
    public $query;
    public $data;
    public $showDropdown = false;

    // Ui settings
    public string $view = 'admin::livewire.auto-complete';
    public string $placeholder = 'Type to search...';
    public string $searchingText = 'Searching...';

    public function mount()
    {
         if ($this->selectedItem) {
              $this->refreshQueryData();
         }
    }

    public function updatedQuery()
    {
        $this->selectedItem = false;
        $this->refreshQueryData();
    }

    public function refreshQueryData()
    {
        //
    }

    public function closeDropdown()
    {
        $this->showDropdown = false;
    }

    public function showDropdown()
    {
        $this->showDropdown = true;
    }

    public function resetProperties()
    {
        $this->query = '';
        $this->data = false;
    }

    public function selectItem(int $id)
    {
        $this->selectedItem = $id;
        $this->refreshQueryData();
        $this->emitSelf('$refresh');

        $this->emit('autoCompleteSelectItem', $this->selectedItemKey, $this->selectedItem);
    }

    public function render()
    {
        return view($this->view);
    }
}
