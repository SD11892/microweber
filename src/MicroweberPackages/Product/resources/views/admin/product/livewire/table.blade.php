<div class="card style-1 mb-3">

    <div class="card-header d-flex align-items-center justify-content-between px-md-4">
        <div class="col d-flex justify-content-md-start justify-content-center align-items-center px-0">
            <h5 class="mb-0 d-flex">
                <i class="mdi mdi-shopping text-primary mr-md-3 mr-1 justify-contetn-center"></i>
                <strong class="d-xl-flex d-none">{{_e('Shop')}} {{_e('Products')}}</strong>
            </h5>
            <a href="{{route('admin.product.create')}}" class="btn btn-outline-success btn-sm js-hide-when-no-items ms-md-4 ms-1">{{_e('Add Product')}}</a>
        </div>
    </div>

    <div class="card-body pt-3">

    @include('product::admin.product.livewire.table-includes.table-tr-reoder-js')

    <div class="d-flex">
        <div class="ms-0 ms-md-2 mb-3 mb-md-0">
            <input wire:model.stop="filters.keyword" type="text" placeholder="Search by keyword..." class="form-control" style="width: 250px;">
        </div>

        <div class="ms-0 ms-md-2 mb-3 mb-md-0">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-plus-circle"></i> Filters
            </button>
            <div class="dropdown-menu p-3" style="width:263px">
                <h6 class="dropdown-header">Taxonomy</h6>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.category"> Category</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.tags"> Tags</label>
                <h6 class="dropdown-header">Shop</h6>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.priceBetween"> Price Range</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.stockStatus"> Stock Status</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.discount"> Discount</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.orders"> Orders</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.qty"> Quantity</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.sku"> Sku</label>
                <h6 class="dropdown-header">Other</h6>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.visible"> Visible</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.userId"> Author</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.dateBetween"> Date Range</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.createdAt"> Created at</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.updatedAt"> Updated at</label>
            </div>
        </div>

        @if(!empty($appliedFiltersFriendlyNames))
            <div class="ms-0 ms-md-2 mb-3 mb-md-0">
                <div class="btn-group">
                    <button class="btn btn-outline-danger" wire:click="clearFilters">Clear</button>
                </div>
            </div>
        @endif
    </div>

    <div class="d-flex flex-wrap mt-3">
        @if(isset($showFilters['category']) && $showFilters['category'])
            @include('product::admin.product.livewire.table-filters.category')
        @endif

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

    <div style="height: 60px" class="bulk-actions-show-columns">

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
                    <li><button class="dropdown-item" type="button" wire:click="multipleDelete">Delete</button></li>
                </ul>
            </div>
        </div>
        @endif

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

    <table class="table table-responsive" id="content-results-table">
        <thead>
        <tr>
            <th scope="col">

                <div class="custom-control custom-checkbox mb-0">
                    <input type="checkbox" wire:model="selectAll" id="secectall-procuts"  class="js-select-posts-for-action custom-control-input"  wire:model="checked">
                    <label for="secectall-procuts" class="custom-control-label"></label>
                </div>

            </th>
            @if($showColumns['id'])
                @include('product::admin.product.livewire.table-includes.table-th',['name'=>'ID', 'key'=>'id', 'filters'=>$filters])
            @endif
            @if($showColumns['image'])
            <th scope="col">Image</th>
            @endif
            @if($showColumns['title'])
            <th scope="col">Title</th>
            @endif
            @if($showColumns['price'])
                @include('product::admin.product.livewire.table-includes.table-th',['name'=>'Price', 'key'=>'price', 'filters'=>$filters])
            @endif
            @if($showColumns['stock'])
            <th scope="col">Stock</th>
            @endif
            @if($showColumns['orders'])
                @include('product::admin.product.livewire.table-includes.table-th',['name'=>'Orders', 'key'=>'orders', 'filters'=>$filters])
            @endif
            @if($showColumns['quantity'])
            <th scope="col">Quantity</th>
            @endif
            @if($showColumns['author'])
            <th scope="col">Author</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @if (count($products)==0)
            <tr>
                <td colspan="{{count($showColumns)}}">
                    {{_e('No items found')}}
                </td>
            </tr>
        @endif
        @foreach ($products as $product)
        <tr class="manage-post-item">
            <td>


                <div class="custom-control custom-checkbox">
                    <input type="checkbox" value="{{ $product->id }}" id="products-{{ $product->id }}"  class="js-select-posts-for-action custom-control-input"  wire:model="checked">
                    <label for="products-{{ $product->id }}" class="custom-control-label"></label>
                </div>

                <span class="btn btn-link text-muted px-0 js-move mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()">
                    <i class="mdi mdi-cursor-move"></i>
                </span>
            </td>
            @if($showColumns['id'])
                <td>
                    {{ $product->id }}
                </td>
            @endif
            @if($showColumns['image'])
            <td>
                @if($product->media()->first())
                <img src="{{$product->thumbnail()}}" class="w-8 h-8 rounded-full">
                @else
                    <div class="img-circle-holder border-radius-0 border-0">
                        <i class="mdi mdi-shopping mdi-48px text-muted text-opacity-5"></i>
                    </div>
                @endif
            </td>
            @endif
            @if($showColumns['title'])
            <td>
                <div class="manage-item-main-top">

                    <a target="_self" href="" class="btn btn-link p-0">
                        <h5 class="text-dark text-break-line-1 mb-0 manage-post-item-title">
                            {{$product->title}}
                        </h5>
                    </a>
                    @if($product->categories->count() > 0)
                        <span class="manage-post-item-cats-inline-list">
                        @foreach($product->categories as $category)
                                @if($category->parent)
                                    <a href="#" class="btn btn-link p-0 text-muted">{{$category->parent->title}}</a>
                                @endif
                            @endforeach
                         </span>
                    @endif
                    <a class="manage-post-item-link-small mw-medium d-none d-lg-block" target="_self"
                       href="{{$product->link()}}">
                        <small class="text-muted">{{$product->link()}}</small>
                    </a>
                 </div>

                <div class="manage-post-item-links mt-3">
                    <a href="{{route('admin.product.edit', $product->id)}}" class="btn btn-outline-primary btn-sm">Edit</a>
                    <a href="{{route('admin.product.edit', $product->id)}}" class="btn btn-outline-success btn-sm">Live Edit</a>
                    <a href="{{route('admin.product.edit', $product->id)}}" class="btn btn-outline-danger btn-sm">Delete</a>
                    @if ($product->is_active < 1)
                    <a href="{{route('admin.product.edit', $product->id)}}" class="badge badge-warning font-weight-normal">Unpublished</a>
                    @endif
                </div>

            </td>
            @endif
            @if($showColumns['price'])
            <td>
                @php
                if ($product->hasSpecialPrice()) {
                    $price = '<span class="h6" style="text-decoration: line-through;">'.currency_format($product->price).'</span>';
                    $price .= '<br /><span class="h5">'.currency_format($product->specialPrice).'</span>';
                } else {
                    $price = '<span class="h5">'.currency_format($product->price).'</span>';
                }
                @endphp

                {!! $price !!}
            </td>
            @endif
            @if($showColumns['stock'])
            <td>
                @php
                  if ($product->InStock) {
                    $stock = '<span class="badge badge-success badge-sm">In stock</span>';
                } else {
                    $stock = '<span class="badge badge-danger badge-sm">Out Of Stock</span>';
                }
                @endphp
                {!! $stock !!}
            </td>
            @endif
            @if($showColumns['orders'])
            <td style="text-align: center">
                @php
                $ordersUrl = route('admin.order.index') . '?productId='.$product->id;
                if ($product->ordersCount == 1) {
                    $sales = '<a href="'.$ordersUrl.'"><span class="text-green">'.$product->ordersCount.'</span></a>';
                } else if ($product->ordersCount > 1) {
                    $sales = '<a href="'.$ordersUrl.'"><span class="text-green">'.$product->ordersCount.'</span></a>';
                } else {
                    $sales = '<span>'.$product->ordersCount.'</span>';
                }
                @endphp
                {!! $sales !!}
            </td>
            @endif
            @if($showColumns['quantity'])
            <td style="text-align: center">
            @php
                if ($product->qty == 'nolimit') {
                      $quantity = '<span><i class="fa fa-infinity" title="Unlimited Quantity"></i></span>';
                  } else if ($product->qty == 0) {
                      $quantity = '<span class="text-small text-danger">0</span>';
                  } else {
                      $quantity = $product->qty;
                  }
            @endphp
                {!! $quantity !!}
            </td>
            @endif
            @if($showColumns['author'])
            <td>
                {{$product->authorName()}}
            </td>
            @endif

        </tr>
        @endforeach
        </tbody>
    </table>

    {{ $products->links() }}

</div>
</div>


