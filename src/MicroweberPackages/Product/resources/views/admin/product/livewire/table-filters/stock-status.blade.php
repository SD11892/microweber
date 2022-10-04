<div class="ms-0 ms-md-2 mb-3 mb-md-0">
    @php
        $data = [
            ['key'=>'any','value'=>'Any'],
            ['key'=>'inStock','value'=>'In Stock'],
            ['key'=>'outOfStock','value'=>'Out Of Stock'],
        ];

        $selectedItem = [];
        if (isset($filters['stockStatus'])) {
            $selectedItem = $filters['stockStatus'];
        }
    @endphp
    @livewire('admin-filter-item', [
        'name'=>'Stock Status',
        'selectedItem'=>$selectedItem,
        'selectedItemKey'=>'stockStatus',
        'data'=>$data
    ])
</div>
