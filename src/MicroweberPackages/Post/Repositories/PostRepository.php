<?php

namespace MicroweberPackages\Post\Repositories;

use MicroweberPackages\Core\Repositories\BaseRepository;
use MicroweberPackages\Post\Post;
use MicroweberPackages\Post\Events\PostIsCreating;
use MicroweberPackages\Post\Events\PostIsUpdating;
use MicroweberPackages\Post\Events\PostWasCreated;
use MicroweberPackages\Post\Events\PostWasDeleted;
use MicroweberPackages\Post\Events\PostWasUpdated;

class PostRepository extends BaseRepository
{

    public function create($request)
    {
        event($event = new PostIsCreating($request));

        $post = Post::create($request);

        event(new PostWasCreated($post, $request));

        return $post->id;
    }

    public function update($post, $request)
    {
        event($event = new PostIsUpdating($post, $request));

        $post->update($request);

        event(new PostWasUpdated($post, $request));

        return $post->id;
    }


    public function destroy($post)
    {
        event(new PostWasDeleted($post));

        return $post->delete();
    }

}
