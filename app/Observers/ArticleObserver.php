<?php

namespace App\Observers;

use App\Models\Article;

class ArticleObserver
{
    /**
     * Handle the Article "deleting" event.
     *
     * @param Article $article
     * @return void
     */
    public function deleting(Article $article): void
    {
        $article->image()->delete();
        $article->authors()->detach();
        $article->tags()->detach();
        $article->categories()->detach();
    }
}
