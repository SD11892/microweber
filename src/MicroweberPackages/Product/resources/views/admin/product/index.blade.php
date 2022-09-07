<div class="card style-1 mb-3">
    <div class="card-header d-flex col-12 align-items-center justify-content-between px-md-4">

        <div class="col d-flex justify-content-md-start justify-content-center align-items-center px-0">
            <h5 class="mb-0">
                <i class="mdi mdi-shopping text-primary mr-md-3 mr-1 justify-contetn-center"></i>
                <strong class="d-xl-flex d-none">{{_e('Shop')}}</strong>
            </h5>
            <a href="{{route('admin.product.create')}}" class="btn btn-outline-success btn-sm js-hide-when-no-items ml-md-2 ml-1">{{_e('Add Product')}}</a>
        </div>

    </div>

    <div class="card-body pt-3">

        <script>
            assignSelectedPostsToCategory = function () {
                $.get("<?php print  api_url('content/get_admin_js_tree_json'); ?>", function (data) {
                    var btn = document.createElement('button');
                    btn.disabled = true;
                    btn.className = 'mw-ui-btn';
                    btn.innerHTML = mw.lang('Move posts');
                    btn.onclick = function (ev) {
                        assign_selected_posts_to_category_exec();
                    };
                    var dialog = mw.dialog({
                        height: 'auto',
                        autoHeight: true,
                        id: 'pick-categories',
                        footer: btn,
                        title: mw.lang('Select categories')
                    });
                    var tree = new mw.tree({
                        data: data,
                        element: dialog.dialogContainer,
                        sortable: false,
                        selectable: true,
                        multiPageSelect: false
                    });
                    $(tree).on("selectionChange", function () {
                        btn.disabled = tree.getSelected().length === 0;
                    });
                    $(tree).on("ready", function () {
                        dialog.center();
                    })
                });
            };
        </script>

        <livewire:admin-products-table />

    </div>
</div>
