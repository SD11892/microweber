<module type="admin/modules/info"/>

<div class="card style-1 mb-3">

    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="admin/import_export_tool" />
    </div>

    <div class="card-body pt-3">
        <div class="row">
        <div class="col-md-12">
            @if(empty($import_feed_names))

                <livewire:import_export_tool_no_feeds />

            @else
                <label for="feed_type"><b>Select import:</b></label>
                <select class="form-control form-control-sm" onchange="window.location.href=this.value">
                    <option value="0">- select -</option>
                    @foreach($import_feed_names as $feedId=>$feedName)
                        <option value="{{route('admin.import-export-tool.import',  $feedId)}}">{{$feedName}}</option>
                    @endforeach
                </select>
            @endif
        </div>
        </div>
    </div>
</div>

