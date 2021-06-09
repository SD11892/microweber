<?php
$randomId = uniqid();
?>

<div class="card-header bg-white px-1">
    <div data-toggle="collapse" data-target="#collapse_{{$randomId}}" aria-expanded="true" class="d-flex">
        <h4 class="title"><?php _e('Search') ?></h4>
        <i class="mdi mdi-plus ml-auto align-self-center" ></i>
    </div>
</div>

<div class="collapse show" id="collapse_{{$randomId}}">
    <div class="card-body px-1">
        <div class="input-group mb-3">
            <input type="text" class="form-control js-filter-search-field" value="{{$search}}" placeholder="<?php _e('Search');?>" />
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary btn-icon pl-3 pr-2 js-filter-search-submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </div>
</div>
