<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Livewire\Component;

class DropdownComponent extends Component
{

    /**
     * @var string
     */
    public $name = 'MyDropdown';

    /**
     * Show/Hide dropdown on view
     * @var bool
     */
    public $showDropdown = false;


    /**
     * Default view of dropdown
     * @var string
     */
    public string $view = 'admin::livewire.dropdown';

    public $listeners = [
        'showDropdown'=>'showDropdown',
        'closeDropdown'=>'closeDropdown',
    ];

    /**
     * @return void
     */
    public function closeDropdown($wireElementId = false)
    {
        if ($wireElementId == $this->id) {
            $this->showDropdown = false;
        }
    }

    /**
     * @return void
     */
    public function showDropdown($wireElementId = false)
    {
        if ($wireElementId == $this->id) {
            $this->showDropdown = true;
        }
    }

    public function render()
    {
        return view($this->view);
    }
}
