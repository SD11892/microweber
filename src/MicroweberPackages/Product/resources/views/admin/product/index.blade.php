<style>
    .badge-dropdown {
        background: #ffffff;
        padding: 20px;
        position: absolute;
        z-index: 15;
        box-shadow: 0px 4px 12px #00000054;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
        border-top-right-radius: 4px;
        border-top:4px solid #6c757d;
        width:300px;
        margin-top:10px;
        visibility:hidden;
        opacity:0;
        transition: 0.3s;
        transform: scale(0);
        transform-origin: center top;
    }
    .badge-dropdown.active {
        visibility:visible;
        transform: scale(1);
        opacity:1;
    }

    .badge-dropdown::after {
        content: ' ';
        width: 0px;
        height: 0px;
        border-style: solid;
        border-width: 0 10px 10px 10px;
        border-color: transparent transparent #6c757d transparent;
        display: inline-block;
        vertical-align: middle;
        position: absolute;
        top: -13px;
    }

   /* .btn-badge-dropdown::after {
        display: inline-block;
        margin-left: 0.255em;
        vertical-align: 0.255em;
        content: "";
        border-top: 0.3em solid;
        border-right: 0.3em solid transparent;
        border-bottom: 0;
        border-left: 0.3em solid transparent;
    }*/

     .btn-badge-dropdown .action-dropdown-icon {
         cursor: pointer;
         float: right;
         width: 16px;
         padding-top: 0px;
         margin-left: 5px;
         font-size:12px;
     }
     .btn-secondary .action-dropdown-icon {
         color: #ffffff91;
     }

    .btn-secondary:hover .action-dropdown-icon {
        color:#FFFFFF;
    }

    .btn-badge-dropdown .action-dropdown-delete {
       cursor: pointer;
       float:right;
       width:24px;
       height:15px;
       padding-left:8px;
    }

    .btn-secondary .action-dropdown-delete {
         color: #ffffff91;
     }

    .btn-secondary .action-dropdown-delete:hover {
        color:#FFFFFF;
    }

    .badge-filter-item {
        background: #fff;
        color: #757575;
        border-radius: 15px;
        padding: 5px 6px;
        margin: 0px 12px;
        font-size: 12px;
    }
</style>

<div class="row">
    <div class="col-md-2">
        <div id="js-page-tree"></div>

        <script>
            var someElement = document.getElementById('js-page-tree');
            var pagesTree;
            mw.admin.tree(someElement, {
                options: {
                    sortable: false,
                    selectable: false,
                    singleSelect: true,
                    saveState: true,
                    searchInput: true
                },
                params: {
                    is_shop: '1'
                }
            }).then(function (res) {
                pagesTree = res;
            });
        </script>
    </div>
    <div class="col-md-10">
        <livewire:admin-products-list />
        <livewire:admin-content-bulk-options />
    </div>
</div>
