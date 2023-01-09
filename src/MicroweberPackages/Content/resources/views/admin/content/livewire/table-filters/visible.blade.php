<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $data = [
            ['key'=>'any','value'=>'Any'],
            ['key'=>'published','value'=>'Published'],
            ['key'=>'unpublished','value'=>'Unpublished'],
        ];

        $selectedItem = [];
        if (isset($filters['visible'])) {
            $selectedItem = $filters['visible'];
        }
    @endphp

    @livewire('admin-filter-item', [
        'name'=>'Visible',
        'searchable'=>false,
        'selectedItem'=>$selectedItem,
        'selectedItemKey'=>'visible',
        'data'=>$data,
        'showDropdown'=> session()->get('showFilterVisible'),
        'onChangedEmitEvents' => [
            'setFirstPageContentList'
        ]
    ])

</div>
