<div id="content-results-table">
    @foreach ($pages as $page)

        <div class="card card-product-holder mb-2 post-has-image-true manage-post-item">
            <div class="card-body">
                <div class="row align-items-center flex-lg-box">

                    <div class="col text-center manage-post-item-col-1" style="max-width: 40px;">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" value="{{ $page->id }}" id="products-{{ $page->id }}"  class="js-select-posts-for-action custom-control-input"  wire:model="checked">
                            <label for="products-{{ $page->id }}" class="custom-control-label"></label>
                        </div>
                        <span class="btn btn-link text-muted px-0 js-move mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()">
                            <i class="mdi mdi-cursor-move"></i>
                        </span>
                    </div>

                    <div class="col manage-post-item-col-2" style="max-width: 120px;">

                        <div class="mw-admin-product-item-icon text-muted">
                            <i class="mdi mdi-shopping mdi-18px" data-bs-toggle="tooltip" title=""></i>
                        </div>

                        @if($page->media()->first())
                            <img src="{{$page->thumbnail(200,200)}}" class="rounded-full">
                        @else
                            @include('content::admin.content.livewire.components.icon', ['content'=>$page])
                        @endif

                    </div>

                    <div class="col item-title manage-post-item-col-3 manage-post-main">

                        <div class="manage-item-main-top">

                            <a target="_self" href="{{route('admin.product.edit', $page->id)}}" class="btn btn-link p-0">
                                <h5 class="text-dark text-break-line-1 mb-0 manage-post-item-title">
                                    {{$page->title}}
                                </h5>
                            </a>
                            @if($page->categories->count() > 0)
                                <span class="manage-post-item-cats-inline-list">
                                @foreach($page->categories as $category)
                                        @if($category->parent)

                                            <a onclick="livewire.emit('selectCategoryFromTableList', {{$category->parent->id}});return false;" href="?filters[category]={{$category->parent->id}}&showFilters[category]=1"
                                               class="btn btn-link p-0 text-muted">
                                        {{$category->parent->title}}
                                    </a>

                                        @endif
                                    @endforeach
                             </span>
                            @endif
                            <a class="manage-post-item-link-small mw-medium d-none d-lg-block" target="_self"
                               href="{{$page->link()}}">
                                <small class="text-muted">{{$page->link()}}</small>
                            </a>
                        </div>


                        <div class="manage-post-item-links mt-3">
                            <a href="{{route('admin.product.edit', $page->id)}}" class="btn btn-outline-primary btn-sm">Edit</a>
                            <a href="{{route('admin.product.edit', $page->id)}}" class="btn btn-outline-success btn-sm">Live Edit</a>
                            <?php if(!$page->is_deleted): ?>
                            <a href="javascript:mw.admin.content.delete('{{ $page->id }}');" class="btn btn-outline-danger btn-sm js-delete-content-btn-{{ $page->id }}">Delete</a>
                            <?php endif; ?>
                            @if ($page->is_active < 1)
                                <a href="javascript:mw.admin.content.publishContent('{{ $page->id }}');" class="mw-set-content-unpublish badge badge-warning font-weight-normal">Unpublished</a>

                            @endif
                        </div>

                        <?php
                        if ($page->is_deleted) {
                            $data = $page->toArray();
                            include(modules_path() . 'content/views/content_delete_btns.php');
                        }
                        ?>

                    </div>

                    <div class="col item-author manage-post-item-col-4 d-xl-block d-none">
                        <span class="text-muted" title="{{$page->authorName()}}">{{$page->authorName()}}</span>
                    </div>

                </div>
            </div>
        </div>

    @endforeach
</div>
