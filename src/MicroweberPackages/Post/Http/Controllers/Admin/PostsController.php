<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */
namespace MicroweberPackages\Post\Http\Controllers\Admin;

use MicroweberPackages\Crud\Traits\HasCrudActions;
use MicroweberPackages\Post\Http\Requests\PostRequest;
use MicroweberPackages\Post\Post;

class PostsController
{
    use HasCrudActions;

    public $model = Post::class;
    public $validator = PostRequest::class;

}