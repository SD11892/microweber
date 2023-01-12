@php
    if ($countActiveContents > 0) {
    $isInTrashed  = false;
    if(isset($showFilters['trashed']) && $showFilters['trashed']){
        $isInTrashed  = true;
    }

    $findCategory = false;
    if (isset($filters['category'])) {
        $findCategory = get_category_by_id($filters['category']);
    }
@endphp

<div class="card style-1 mb-3">

    <div class="card-header d-flex align-items-center justify-content-between px-md-4">
        <div class="col d-flex justify-content-md-between justify-content-center align-items-center px-0">
            <h5 class="mb-0 d-flex">
                <i class="mdi mdi-earth text-primary mr-md-3 mr-1 justify-content-center"></i>
                <strong class="d-md-flex d-none">
                    <a  class="<?php if($findCategory): ?> text-decoration-none <?php else: ?> text-decoration-none text-dark <?php endif; ?>" onclick="livewire.emit('deselectAllCategories');return false;">{{_e('Website')}}</a>

                    @if($findCategory)
                        <span class="text-muted">&nbsp; &raquo; &nbsp;</span>
                        {{$findCategory['title']}}
                    @endif

                    @if($isInTrashed)
                        <span class="text-muted">&nbsp; &raquo; &nbsp;</span>  <i class="mdi mdi-trash-can"></{{ _e('Trash') }}
                    @endif
                </strong>

                @if($findCategory)
                    <a class="ms-1 text-muted fs-5"  onclick="livewire.emit('deselectAllCategories');return false;">
                        <i class="fa fa-times-circle"></i>
                    </a>
                @endif
            </h5>
            <div>
                @if($findCategory)
                    <a href="{{category_link($findCategory['id'])}}" target="_blank" class="btn btn-link btn-sm js-hide-when-no-items ms-md-4">{{_e('View category')}}</a>
                @endif
            </div>
        </div>
    </div>

    <div class="card-body pt-3">

        @include('content::admin.content.livewire.table-includes.table-tr-reoder-js')

        @php
            $showFiltersUnsetCategory = $showFilters;
            if (isset($showFiltersUnsetCategory['category'])) {
                unset($showFiltersUnsetCategory['category']);
            }

            $displayFilters = true;
            if ($contents->total() == 0 && empty($showFiltersUnsetCategory)) {
                $displayFilters = false;
            }
             $filtersUnsetCategory = $filters;
            if (isset($filtersUnsetCategory['category'])) {
                unset($filtersUnsetCategory['category']);
            }
            if (empty($filtersUnsetCategory)) {
                $displayFilters = false;
            }
            if (!empty($filtersUnsetCategory)) {
                $displayFilters = true;
            }
             if ($products->total() > 0) {
                $displayFilters = true;
            }
        @endphp

        @if($displayFilters)
        <div class="d-flex flex-wrap">

            <?php if(!$isInTrashed): ?>

            @include('content::admin.content.livewire.components.keyword')

            <div class="col-xl-2 col-sm-3 col-12 mb-3 mb-md-0 ps-0">
                @include('content::admin.content.livewire.components.button-filter')
                <div class="dropdown-menu p-3" style="width:263px">
                    <h6 class="dropdown-header">{{ _e('Taxonomy') }}'</h6>
                    {{--<label class="dropdown-item"><input type="checkbox" wire:model="showFilters.category"> Category</label>--}}
                    <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.tags"> {{ _e('Tags') }}</label>
                    <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.visible"> {{ _e('Visible') }}</label>
                    <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.userId"> {{ _e('Author') }}</label>
                    <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.dateBetween"> {{ _e('Date Range') }}</label>
                    <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.createdAt"> {{ _e('Created at') }}</label>
                    <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.updatedAt"> {{ _e('Updated at') }}</label>
                </div>
            </div>

            <?php endif; ?>

            @if(!empty($appliedFiltersFriendlyNames))
                @include('content::admin.content.livewire.components.button-clear-filters')
            @endif
        </div>
        @endif

        <div class="d-flex flex-wrap mt-3">

            @if(isset($showFilters['tags']) && $showFilters['tags'])
                @include('content::admin.content.livewire.table-filters.tags')
            @endif

            @if(isset($showFilters['visible']) && $showFilters['visible'])
                @include('content::admin.content.livewire.table-filters.visible')
            @endif

            @if(isset($showFilters['userId']) && $showFilters['userId'])
                @include('content::admin.content.livewire.table-filters.author')
            @endif


            @if(isset($showFilters['dateBetween']) && $showFilters['dateBetween'])
                @include('content::admin.content.livewire.table-filters.date-between')
            @endif

            @if(isset($showFilters['createdAt']) && $showFilters['createdAt'])
                @include('content::admin.content.livewire.table-filters.created-at')
            @endif

            @if(isset($showFilters['updatedAt']) && $showFilters['updatedAt'])
                @include('content::admin.content.livewire.table-filters.updated-at')
            @endif
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
        <div class="row mt-3">

            <div class="d-flex flex-wrap bulk-actions-show-columns mw-js-loading position-relative mb-1">

                @if($contents->total() > 0)


                @include('content::admin.content.livewire.components.display-as')


                <div class="col-md-7 col-12 d-flex justify-content-end align-items-center px-0 mw-filters-sorts-mobile">

                    @include('content::admin.content.livewire.components.sort')
                    @include('content::admin.content.livewire.components.limit')

                    <div class="">
                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle ms-2" style="padding: 10px;" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ _e('Columns') }}
                        </button>
                        <div class="dropdown-menu p-3">
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.id"> {{ _e('Id') }}</label>
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.image"> {{ _e('Image') }}</label>
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.title"> {{ _e('Title') }}</label>
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.author"> {{ _e('Author') }}</label>
                        </div>
                    </div>
                </div>
                @endif



                    <script>
                        mw.spinner({
                            size: 30,
                            element: ".mw-js-loading",
                            decorate: true,

                        });

                        mw.spinner({
                            size: 30,
                            element: ".mw-js-loading",
                            decorate: true,

                        }).remove();
                    </script>
            </div>


        </div>
        @if($contents->total() > 0)

            <div class="row mt-3">
                <div class="col-md-12">
                    @if($displayType == 'card')
                        @include('content::admin.content.livewire.display-types.card')
                    @endif

                    @if($displayType == 'table')
                        @include('content::admin.content.livewire.display-types.table')
                    @endif
                </div>
            </div>

            {{ $contents->links() }}

        @else
            @include('content::admin.content.livewire.no-results-for-filters')
        @endif

    </div>
</div>

@php
    } else {
@endphp

@include('content::admin.content.livewire.no-results')

@php
    }
@endphp

