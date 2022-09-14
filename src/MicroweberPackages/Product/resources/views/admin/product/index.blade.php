<div class="card style-1 mb-3">
    <div class="card-header d-flex col-12 align-items-center justify-content-between px-md-4">

        <div class="col d-flex justify-content-md-start justify-content-center align-items-center px-0">
            <h5 class="mb-0">
                <i class="mdi mdi-shopping text-primary mr-md-3 mr-1 justify-contetn-center"></i>
                <strong class="d-xl-flex d-none">{{_e('Shop')}} {{_e('Products')}}</strong>
            </h5>
            <a href="{{route('admin.product.create')}}" class="btn btn-outline-success btn-sm js-hide-when-no-items ml-md-2 ml-1">{{_e('Add Product')}}</a>
        </div>

    </div>

    <div class="card-body pt-3">
        <livewire:admin-products-index />
        <livewire:content-bulk-options />
    </div>
</div>

