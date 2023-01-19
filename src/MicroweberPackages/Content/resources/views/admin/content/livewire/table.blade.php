<div class="card style-1 mb-3">

    @include('content::admin.content.livewire.card-header')

    <div class="card-body pt-3">

        @include('content::admin.content.livewire.table-includes.table-tr-reoder-js')

        @if($displayFilters)
        <div class="d-flex flex-wrap">

            @include('content::admin.content.livewire.components.keyword')

            <div class="col-xl-2 col-sm-3 col-12 mb-3 mb-md-0 ps-0">
                @include('content::admin.content.livewire.components.button-filter')
                <div class="dropdown-menu p-1" style="width:250px;max-height:400px;overflow:auto;overflow-x:hidden;">

                    @if(!empty($dropdownFilters))
                        @foreach($dropdownFilters as $dropdownFilterGroup)
                            <div>
                                 <h6 class="dropdown-header">{{ $dropdownFilterGroup['groupName']  }}</h6>
                                @foreach($dropdownFilterGroup['filters'] as $dropdownFilter)
                                    <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.{{ $dropdownFilter['key'] }}"> {{ $dropdownFilter['name'] }}</label>
                                @endforeach
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>

            @if(!empty($appliedFiltersFriendlyNames))
                @include('content::admin.content.livewire.components.button-clear-filters')
            @endif
        </div>
        @endif

        <div class="d-flex flex-wrap mt-3">

        @php
        if(!empty($dropdownFilters)) {
            foreach($dropdownFilters as $dropdownFilterGroup) {
                foreach($dropdownFilterGroup['filters'] as $dropdownFilter) {
                    $skipDropdownFilter = false;
                    if(isset($dropdownFilter['type']) && $dropdownFilter['type'] == 'separator') {
                        $skipDropdownFilter = true;
                    }
                    if (!$skipDropdownFilter) {

                        if (isset($dropdownFilter['key']) && strpos($dropdownFilter['key'], '.') !== false) {
                                $dropdownFilterExp = explode('.', $dropdownFilter['key']);
                                if (isset($showFilters[$dropdownFilterExp[0]][$dropdownFilterExp[1]])) {
                                    @endphp
                                         @include('content::admin.content.livewire.table-filters.' . $dropdownFilterExp[0], [
                                            'fieldName'=>$dropdownFilter['name'],
                                            'fieldKey'=>$dropdownFilterExp[1],
                                            'fieldValue'=>$showFilters[$dropdownFilterExp[0]][$dropdownFilterExp[1]],
                                           ])
                                    @php
                                }
                            continue;
                        }


                        if (isset($showFilters[$dropdownFilter['key']]) && $showFilters[$dropdownFilter['key']]) {
                         @endphp
                            @if (isset($dropdownFilter['viewNamespace']))
                                @include($dropdownFilter['viewNamespace'])
                            @else
                                @include('content::admin.content.livewire.table-filters.'.$dropdownFilter['key'])
                            @endif
                        @php
                        }
                    }
                }
            }
        }
        @endphp

        </div>
        <div class="row  mt-3">
            @if(count($checked) > 0)

                @if (count($checked) == count($contents->items()))
                    <div class="col-md-10 mb-2">
                        You have selected all {{ count($checked) }} items.
                        <button type="button" class="btn btn-outline-danger btn-sm" wire:click="deselectAll">{{ _e('Deselect All') }}</button>
                    </div>
                @else
                    <div>
                        You have selected {{ count($checked) }} items,
                        Do you want to Select All {{ count($contents->items()) }}?
                        <button type="button" class="btn btn-link btn-sm" wire:click="selectAll">{{ _e('Select All') }}</button>
                    </div>
                @endif
            @endif

            @if(count($checked) > 0)
                <div class="pull-left">
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ _e('Bulk Actions') }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><button class="dropdown-item" type="button" wire:click="multipleMoveToCategory">{{ _e('Move To Category') }}</button></li>
                            <li><button class="dropdown-item" type="button" wire:click="multiplePublish">{{ _e('Publish') }}</button></li>
                            <li><button class="dropdown-item" type="button" wire:click="multipleUnpublish">{{ _e('Unpublish') }}</button></li>
                            <li><button class="dropdown-item" type="button" wire:click="multipleDelete">{{ _e('Move to trash') }}</button></li>
                            <li><button class="dropdown-item" type="button" wire:click="multipleDeleteForever">{{ _e('Delete Forever') }}</button></li>
                            <?php if($isInTrashed): ?>
                            <li><button class="dropdown-item" type="button" wire:click="multipleUndelete">{{ _e('Restore from trash') }}</button></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        @if($contents->total() > 0)
            <div class="row mt-3">
                <div class="d-flex flex-wrap bulk-actions-show-columns mw-js-loading position-relative mb-1">

                    @include('content::admin.content.livewire.components.display-as')

                    <div class="col-md-7 col-12 d-flex justify-content-end align-items-center px-0 mw-filters-sorts-mobile">

                        @include('content::admin.content.livewire.components.sort')
                        @include('content::admin.content.livewire.components.limit')

                        <div class="">
                            <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle ms-2" style="padding: 10px;" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ _e('Columns') }}
                            </button>
                            <div class="dropdown-menu p-3">
                                @foreach($showColumns as $column=>$columnShow)
                                <label wire:key="show-column-{{ $loop->index }}" class="dropdown-item"><input type="checkbox" wire:model="showColumns.{{$column}}"> {{ _e(ucfirst($column)) }}</label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">

                    @if($displayType == 'card')
                        @if(isset($this->displayTypesViews['card']))
                            @include($this->displayTypesViews['card'])
                        @else
                            @include('content::admin.content.livewire.display-types.card')
                        @endif
                    @endif

                    @if($displayType == 'table')
                        @if(isset($this->displayTypesViews['table']))
                            @include($this->displayTypesViews['table'])
                        @else
                            @include('content::admin.content.livewire.display-types.table')
                        @endif
                    @endif

                </div>
            </div>

            <div class="d-flex justify-content-center">

                <div style="width: 100%">
                    <span class="text-muted">{{ $contents->total() }} results found</span>
                </div>

                <div>
                    {{ $contents->links() }}
                </div>
            </div>

        @else
            @include('content::admin.content.livewire.no-results-for-filters')
        @endif

    </div>
</div>
