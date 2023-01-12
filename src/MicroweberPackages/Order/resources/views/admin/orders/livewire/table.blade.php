<div>

    <div wire:loading>
        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <div class="row">
    <div class="col-md-12">
        <div style="height: 60px;" class="bulk-actions-show-columns flex">


            @if(count($checked) > 0)
                <div class="flex">

                @if (count($checked) == count($orders->items()))
                    <div >
                        You have selected all {{ count($checked) }} items.
                        <button type="button" class="btn btn-outline-danger btn-sm" wire:click="deselectAll">Deselect All</button>
                    </div>
                @else
                    <div>
                        You have selected {{ count($checked) }} items,
                        Do you want to Select All {{ count($orders->items()) }}?
                        <button type="button" class="btn btn-outline-primary btn-sm" wire:click="selectAll">Select All</button>
                        <button type="button" class="btn btn-outline-danger btn-sm" wire:click="deselectAll">Deselect All</button>
                    </div>
                @endif

                <div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Bulk Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li><button class="dropdown-item" type="button" wire:click='$emit("openModal", "admin-orders-bulk-order-status", {{ json_encode(["ids" => $checked]) }})'><i class="fa fa-truck"></i> Change Order Status</button></li>
                                <li><button class="dropdown-item" type="button" wire:click='$emit("openModal", "admin-orders-bulk-payment-status", {{ json_encode(["ids" => $checked]) }})'><i class="fa fa-money-bill"></i> Change Payment Status</button></li>
                                <li><button class="dropdown-item" type="button" wire:click='$emit("openModal", "admin-orders-bulk-delete", {{ json_encode(["ids" => $checked]) }})'><i class="fa fa-times"></i> Delete</button></li>
                            </ul>
                        </div>
                </div>

                </div>
            @endif

            <div class="row mt-3">
                <div class="d-flex flex-wrap bulk-actions-show-columns mw-js-loading position-relative mb-1">

                @include('content::admin.content.livewire.components.display-as')

                <div class="col-md-7 col-12 d-flex justify-content-end align-items-center px-0 mw-filters-sorts-mobile">

                    <div class="d-flex align-items-center mx-1">
                        <label class="d-xl-block d-none mx-2">Sort</label>
                        <select class="form-control form-control-sm" wire:model.stop="filters.orderBy" >
                            <option value="">Any</option>
                            <option value="id,desc">Id Desc</option>
                            <option value="id,asc">Id Asc</option>

                            <option value="created_at,desc">Date Desc</option>
                            <option value="created_at,asc">Date Asc</option>
                        </select>
                    </div>

                    <div class="d-flex align-items-center mx-1">
                        <label class="d-xl-block d-none mx-2">Limit</label>
                        <select class="form-control form-control-sm" wire:model="paginationLimit">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="500">500</option>
                        </select>
                    </div>

                    @if($displayType=='table')


                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle ms-2" data-bs-toggle="dropdown" aria-expanded="false">
                                Columns
                            </button>
                            <div class="dropdown-menu p-3">
                                <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.id"> Id</label>
                                <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.image"> Image</label>
                                <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.products"> Products</label>
                                <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.customer"> Customer</label>
                                <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.total_amount"> Total Amount</label>
                                <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.shipping_method"> Shipping Method</label>
                                <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.payment_method"> Payment Method</label>
                                <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.payment_status"> Payment Status</label>
                                <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.status"> Status</label>
                                <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.created_at"> Created At</label>
                                <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.updated_at"> Updated At</label>
                                <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.actions"> Actions</label>
                            </div>
                        </div>

                    @endif
                </div>

              </div>
            </div>

        </div>
    </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            @if($displayType == 'card')
                @include('order::admin.orders.livewire.display-types.card')
            @endif

            @if($displayType == 'table')
                @include('order::admin.orders.livewire.display-types.table')
            @endif
        </div>
    </div>

    <div class="d-flex justify-content-center">

        <div style="width: 100%">
            <span class="text-muted">{{ $orders->total() }} results found</span>
        </div>

        <div>
        {{ $orders->links() }}
        </div>
    </div>

</div>


