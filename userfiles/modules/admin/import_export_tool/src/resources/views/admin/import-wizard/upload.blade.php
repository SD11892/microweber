@extends('import_export_tool::admin.import-wizard.layout')

@section('content')

    <div style="width: 800px;margin:0 auto">

    <div>
        <div class="mb-2">Upload File Type</div>
        <select class="form-control mb-3 w-100" wire:model="import_feed.source_type">
            <option value="download_link">Download feed from link</option>
            <option value="upload_file">Upload feed from your computer</option>
        </select>
    </div>

        <div style="background: #f9f9f9;padding: 30px;">

            @if($this->import_feed['source_type'] == 'upload_file')
                <div>
                    <b>Upload content feed file</b>
                </div>
                <div>
                    <form wire:submit.prevent="upload">
                        <input type="file" wire:model="upload_file">
                        <button type="submit" class="btn btn-outline-primary">Upload</button>
                    </form>
                    @error('upload_file') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            @endif

            @if($this->import_feed['source_type'] == 'download_link')
                <div>
                    <b>Link to content feed file</b>
                </div>
                <div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" wire:model.defer="import_feed.source_file"
                               id="source_file" placeholder="https://site.com/feed.xml">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary" id="source_file"
                                    wire:click="download"
                                    wire:loading.attr="disabled">
                                Download
                            </button>
                        </div>
                    </div>
                    <div wire:loading wire:target="download">
                        <div class="spinner-border spinner-border-sm text-success" role="status"></div>
                        <span class="text-success">
                           Downloading the source file...
                       </span>
                    </div>
                </div>
            @endif

            <div class="mt-2 js-read-feed-from-file" style="display: none">
                <div class="spinner-border spinner-border-sm text-success" role="status"></div>
                <span class="text-success">
                   Reading feed data...
               </span>
            </div>

            <script type="text/javascript">
                window.addEventListener('read-feed-from-file', event => {
                    $('.js-read-feed-from-file').show();
                    window.livewire.emit('readFeedFile');
                });
            </script>

        </div>
    </div>
@endsection
