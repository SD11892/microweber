<div class="main-toolbar" id="mw-modules-toolbar">
    <a href="javascript:;" onClick="history.go(-1)" class="btn btn-link text-silver"><i class="mdi mdi-chevron-left"></i> <?php _e("Back"); ?></a>
</div>

<div class="card style-1 bg-light mb-3">
    <div class="card-header">
        <h5>
            @yield('icon')
            <strong>@yield('title')</strong>
        </h5>
    </div>

    <style>
        .table thead th {
            text-transform: uppercase;
            font-size: 13px;
        }
    </style>

    <div class="card-body pt-3">
        @yield('content')
    </div>
</div>