<div class="form-group d-flex">
    <label class="control-label align-self-center mr-2"><?php _e('Sort');?></label>
    <select class="selectpicker form-control js-filter-change-sort">
        <option disabled="disabled"><?php _e('Select');?></option>
        @foreach($options as $option)
            <option data-sort="{{$option->sort}}" data-order="{{$option->order}}" @if($option->active) selected="selected" @endif>{{$option->name}}</option>
        @endforeach
    </select>
</div>
