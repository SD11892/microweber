@php
    if ($countActiveProducts > 0) {
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
                <i class="mdi mdi-shopping text-primary mr-md-3 mr-1 justify-contetn-center"></i>
                <strong class="d-md-flex d-none">
                 <a  class="<?php if($findCategory): ?> text-decoration-none <?php else: ?> text-decoration-none text-dark <?php endif; ?>" onclick="livewire.emit('deselectAllCategories');return false;">{{_e('Products')}}</a>

                    @if($findCategory)
                        <span class="text-muted">&nbsp; &raquo; &nbsp;</span>
                        {{$findCategory['title']}}
                    @endif

                    @if($isInTrashed)
                        <span class="text-muted">&nbsp; &raquo; &nbsp;</span>  <i class="mdi mdi-trash-can"></i><?php _e('Trash') ?>
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
            <a href="{{route('admin.product.create')}}" class="btn btn-outline-success btn-sm js-hide-when-no-items ms-md-4 card-header-add-button">{{_e('Add Product')}}</a>
            </div>
        </div>
    </div>

    <div class="card-body pt-3">
    @include('product::admin.product.livewire.table-includes.table-tr-reoder-js')


    <div class="d-flex">

       <?php if(!$isInTrashed): ?>
        <div class="ms-0 ms-md-2 mb-3 mb-md-0">
            <input wire:model.stop="filters.keyword" type="search" placeholder="Search by keyword..." class="form-control" style="width: 250px;">
        </div>

        <div class="ms-0 ms-md-2 mb-3 mb-md-0">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-plus-circle"></i> Filters
            </button>
            <div class="dropdown-menu p-3" style="width:263px">
                <h6 class="dropdown-header">Taxonomy</h6>
                {{--<label class="dropdown-item"><input type="checkbox" wire:model="showFilters.category"> Category</label>--}}
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.tags"> Tags</label>
                <h6 class="dropdown-header">Shop</h6>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.priceBetween"> Price Range</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.stockStatus"> Stock Status</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.discount"> Discount</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.orders"> Orders</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.qty"> Quantity</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.sku"> Sku</label>

                @php
                $templateFields = mw()->template->get_edit_fields('product');
                if (!empty($templateFields)): 
                @endphp
                <h6 class="dropdown-header">Template fields</h6>
                @foreach($templateFields as $templateFieldKey=>$templateFieldName)
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.contentFields.{{$templateFieldKey}}"> {{$templateFieldName}}</label>
                @endforeach
                @endif

                <h6 class="dropdown-header">Other</h6>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.visible"> Visible</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.userId"> Author</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.dateBetween"> Date Range</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.createdAt"> Created at</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.updatedAt"> Updated at</label>
            </div>
        </div>

        <?php endif; ?>

        @if(!empty($appliedFiltersFriendlyNames))
            <div class="ms-0 ms-md-2 mb-3 mb-md-0">
                <div class="btn-group">
                    <button class="btn btn-outline-danger" wire:click="clearFilters">Clear</button>
                </div>
            </div>
        @endif
    </div>

    <div class="d-flex flex-wrap mt-3">
      {{--  @if(isset($showFilters['category']) && $showFilters['category'])
            @include('product::admin.product.livewire.table-filters.category')
        @endif--}}

        @if(isset($showFilters['tags']) && $showFilters['tags'])
            @include('product::admin.product.livewire.table-filters.tags')
        @endif

        @if(isset($showFilters['priceBetween']) && $showFilters['priceBetween'])
            @include('product::admin.product.livewire.table-filters.price-between')
        @endif

        @if(isset($showFilters['stockStatus']) && $showFilters['stockStatus'])
            @include('product::admin.product.livewire.table-filters.stock-status')
        @endif

        @if(isset($showFilters['discount']) && $showFilters['discount'])
            @include('product::admin.product.livewire.table-filters.discount')
        @endif

        @if(isset($showFilters['orders']) && $showFilters['orders'])
            @include('product::admin.product.livewire.table-filters.orders')
        @endif

        @if(isset($showFilters['qty']) && $showFilters['qty'])
            @include('product::admin.product.livewire.table-filters.quantity')
        @endif

        @if(isset($showFilters['sku']) && $showFilters['sku'])
            @include('product::admin.product.livewire.table-filters.sku')
        @endif

        @if(isset($showFilters['contentFields']) && $showFilters['contentFields'])

        @endif

        @if(isset($showFilters['visible']) && $showFilters['visible'])
            @include('product::admin.product.livewire.table-filters.visible')
        @endif

        @if(isset($showFilters['userId']) && $showFilters['userId'])
            @include('product::admin.product.livewire.table-filters.author')
        @endif


        @if(isset($showFilters['dateBetween']) && $showFilters['dateBetween'])
            @include('product::admin.product.livewire.table-filters.date-between')
        @endif

        @if(isset($showFilters['createdAt']) && $showFilters['createdAt'])
            @include('product::admin.product.livewire.table-filters.created-at')
        @endif

        @if(isset($showFilters['updatedAt']) && $showFilters['updatedAt'])
            @include('product::admin.product.livewire.table-filters.updated-at')
        @endif
    </div>
        <div class="row  mt-3">
            @if(count($checked) > 0)

                @if (count($checked) == count($products->items()))
                    <div class="col-md-10 mb-2">
                        You have selected all {{ count($checked) }} items.
                        <button type="button" class="btn btn-outline-danger btn-sm" wire:click="deselectAll">Deselect All</button>
                    </div>
                @else
                    <div>
                        You have selected {{ count($checked) }} items,
                        Do you want to Select All {{ count($products->items()) }}?
                        <button type="button" class="btn btn-link btn-sm" wire:click="selectAll">Select All</button>
                    </div>
                @endif
            @endif

            @if(count($checked) > 0)
                <div class="pull-left">
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Bulk Actions
                        </button>
                        <ul class="dropdown-menu">
                            <li><button class="dropdown-item" type="button" wire:click="multipleMoveToCategory">Move To Category</button></li>
                            <li><button class="dropdown-item" type="button" wire:click="multiplePublish">Publish</button></li>
                            <li><button class="dropdown-item" type="button" wire:click="multipleUnpublish">Unpublish</button></li>
                            <li><button class="dropdown-item" type="button" wire:click="multipleDelete">Move to trash</button></li>
                            <li><button class="dropdown-item" type="button" wire:click="multipleDeleteForever">Delete Forever</button></li>
                            <?php if($isInTrashed): ?>

                            <li><button class="dropdown-item" type="button" wire:click="multipleUndelete">Restore from trash</button></li>

                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            @endif
        </div>
        <div class="row  mt-3">



            <div style="height: 60px" class="bulk-actions-show-columns">

                <div class="d-inline-block mx-1">
                    <span class="d-md-block d-none text-muted small"> Display as </span>
                    <div class="btn-group mb-4">
                        <a href="#" wire:click="setDisplayType('card')" class="btn btn-sm btn-outline-primary @if($displayType=='card') active @endif">
                            <i class="fa fa-id-card"></i> <?php _e('Card') ?> </a>
                        <a href="#" wire:click="setDisplayType('table')" class="btn btn-sm btn-outline-primary @if($displayType=='table') active @endif">
                            <i class="fa fa-list"></i> <?php _e('Table') ?> </a>
                    </div>
                </div>

                <div class="pull-right">

                    <div class="d-inline-block mx-1">

                        <span class="d-md-block d-none">Sort</span>
                        <select wire:model.stop="filters.orderBy" class="form-control form-control-sm">
                            <option value="">Any</option>
                            <option value="id,desc">Id Desc</option>
                            <option value="id,asc">Id Asc</option>
                            <option value="price,desc">Price Desc</option>
                            <option value="price,asc">Price Asc</option>
                            <option value="orders,desc">Orders Desc</option>
                            <option value="orders,asc">Orders Asc</option>
                        </select>
                    </div>

                    <div class="d-inline-block mx-1">

                        <span class="d-md-block d-none">Limit</span>
                        <select class="form-control form-control-sm" wire:model="paginate">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="500">500</option>
                        </select>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Show columns
                        </button>
                        <div class="dropdown-menu p-3">
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.id"> Id</label>
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.image"> Image</label>
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.title"> Title</label>
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.price"> Price</label>
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.stock"> Stock</label>
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.orders"> Orders</label>
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.quantity"> Quantity</label>
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.author"> Author</label>
                        </div>
                    </div>
                </div>

                <div class="page-loading" wire:loading>
                    Loading...
                </div>

            </div>


        </div>
        @if($products->total() > 0)


    <div class="row mt-3">
        <div class="col-md-12">
            @if($displayType == 'card')
                @include('product::admin.product.livewire.display-types.card')
            @endif

            @if($displayType == 'table')
                @include('product::admin.product.livewire.display-types.table')
            @endif
        </div>
    </div>


    {{ $products->links() }}
    @else
        @include('product::admin.product.livewire.no-results-for-filters')
    @endif

</div>
</div>

@php
    } else {
@endphp

@include('product::admin.product.livewire.no-results')

@php
    }
@endphp

