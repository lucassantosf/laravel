<?php

namespace App\Observers;

use App\Models\Post;
use App\Jobs\ProcessPostSlugJob;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        ProcessPostSlugJob::dispatch($post);
    }
}
