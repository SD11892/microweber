<?php
$randomId = uniqid();
?>

<div class="card-header bg-white">
    <a href="#" data-toggle="collapse" data-target="#collapse_{{$randomId}}" aria-expanded="true" class="d-flex">
        <h6 class="title">{{$filter->controlName}}</h6>
        <i class="mdi mdi-plus ml-auto"   ></i>
    </a>
</div>

<div class="collapse show" id="collapse_{{$randomId}}">
    <div class="card-body filter-max-scroll">
        <div class="form-group js-slimscroll">
            @foreach($filter->options as $options)
                <div class="custom-control custom-radio">
                    @php
                        $randIdForCheck = uniqid();
                    @endphp
                    <input class="custom-control-input js-filter-option-select" type="radio" id="{{$randIdForCheck}}" @if ($options->active) checked @endif name="filters[{{$filterKey}}][]" value="{{$options->value}}">
                    <label class="custom-control-label" for="{{$randIdForCheck}}">
                        {{ $options->value }}
                    </label>
                </div>
            @endforeach
        </div>

    </div>
</div>
