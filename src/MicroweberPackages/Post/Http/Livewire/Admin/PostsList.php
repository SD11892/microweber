<?php

namespace MicroweberPackages\Post\Http\Livewire\Admin;

use MicroweberPackages\Content\Http\Livewire\Admin\ContentList;
use MicroweberPackages\Post\Models\Post;

class PostsList extends ContentList
{
    public $model = Post::class;

    public $showColumns = [
        'id' => true,
        'image' => true,
        'title' => true,
        'author' => true
    ];

}
