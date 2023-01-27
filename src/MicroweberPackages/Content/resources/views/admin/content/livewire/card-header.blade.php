<div class="card-header d-flex align-items-center justify-content-between px-md-4">
    <div class="col d-flex justify-content-md-between justify-content-center align-items-center px-0">
        <h5 class="mb-0 d-flex">
            <i class="mdi mdi-earth text-primary mr-md-3 mr-1 justify-content-center"></i>

            <strong class="d-md-flex d-none">

                <a class="@if(isset($currentCategory) and $currentCategory) text-decoration-none @else text-decoration-none text-dark @endif" onclick="livewire.emit('deselectAllCategories');return false;">
                    {{_e('Website')}}
                </a>

                @if(isset($currentCategory) and $currentCategory)
                    <span class="text-muted">&nbsp; &raquo; &nbsp;</span>
                    {{$currentCategory['title']}}
                @endif

                @if($isInTrashed)
                    <span class="text-muted">&nbsp; &raquo; &nbsp;</span>
                    <i class="mdi mdi-trash-can"></i> {{ _e('Trash') }}
                @endif

            </strong>

            @if(isset($currentCategory) and $currentCategory)
                <a class="ms-1 text-muted fs-5"  onclick="livewire.emit('deselectAllCategories');return false;">
                    <i class="fa fa-times-circle"></i>
                </a>
            @endif
        </h5>
        <div>

            @if(isset($contentType))
                @include('content::admin.content.livewire.create-content-buttons',['buttonClass'=>'btn btn-link btn-sm'])

            @endif




            <?php
            /*  @if(isset($currentCategory) and $currentCategory)
            <a href="{{category_link($currentCategory['id'])}}" target="_blank" title="{{_e('View category')}}"><span class="fa fa-external-link-alt"></span></a>
            @endif*/

            ?>



        </div>
    </div>
</div>
