<?php

namespace App\RequestMappers;

use App\Models\Article;

class ArticleRequestMapper
{
    public function map(Article $article, array $data): Article
    {
        $authors = data_get($data, 'authors');
        $tags = data_get($data, 'tags', []);
        $categories = data_get($data, 'categories', []);

        $article->fill([
            'title' => data_get($data, 'title'),
            'lead' => data_get($data, 'lead'),
            'body' => data_get($data, 'body'),
            'active' => data_get($data, 'active'),
            'hidden' => data_get($data, 'hidden'),
            'sponsored' => data_get($data, 'sponsored'),
            'is_18_plus' => data_get($data, 'is_18_plus'),
            'image_id' => data_get($data, 'image_id'),
        ]);

        $article->save();

        $article->authors()->sync($authors);
        $article->tags()->sync($tags);
        $article->categories()->sync($categories);

        return $article->refresh();
    }
}
