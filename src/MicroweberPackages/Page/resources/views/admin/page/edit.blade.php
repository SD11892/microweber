@if(isset($recommended_content_id) and isset($recommended_category_id) and $content_id == 0)
    <module type="content/edit" content_id="{{$content_id}}" content_type="page"
            parent="{{$recommended_content_id}}" id="main-content-edit-admin"
            category="{{$recommended_category_id}}"   />
@else
    <module type="content/edit" content_id="{{$content_id}}" content_type="page"  id="main-content-edit-admin" />
@endif
