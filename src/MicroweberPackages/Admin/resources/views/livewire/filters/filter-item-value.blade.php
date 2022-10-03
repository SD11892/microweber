<div class="js-filter-item-dropdown" @if($showDropdown) data-dropdown-show="1" @else data-dropdown-show="0" @endif>

    <button type="button" wire:click="load" class="btn @if(!empty($selectedItem)) btn-primary @else btn-outline-primary @endif btn-sm icon-left">


        @if($itemValue)
            {{$name}}: {{$itemValue}}
        @else
            {{$name}}
        @endif

        <span class="mt-2">&nbsp;</span>

        <i class="ml-2 fa fa-arrow-down" style="font-size: 10px"></i>
    </button>

    <div class="badge-dropdown position-absolute" @if(!$showDropdown) style="display: none" @endif>

        <input type="text" class="form-control" wire:model.stop="itemValue">

    </div>
</div>
