<?php

namespace App\RequestMappers;

use App\Models\Article;
use App\Models\Media;

class ArticleRequestMapper
{
    /**
     * @param Article $article
     * @param array $data
     * @return Article
     */
    public function map(Article $article, array $data): Article
    {
        $article->fill([
            'title' => data_get($data, 'title'),
            'lead' => data_get($data, 'lead'),
            'body' => data_get($data, 'body'),
            'is_sponsored' => data_get($data, 'is_sponsored'),
            'is_18_plus' => data_get($data, 'is_18_plus'),
            'is_active' => data_get($data, 'is_active'),
            'published_at' => data_get($data, 'published_at'),
        ]);

       if (data_get($data, 'image.id')) {
            $article->image()->associate(Media::findOrFail($data['image']['id']));
        }

        $article->save();

        $article->authors()->sync(data_get($data, 'authors'));
        $article->tags()->sync(data_get($data, 'tags'));
        $article->categories()->sync(data_get($data, 'categories'));

        $article->refresh();

        return $article->load(['authors', 'tags', 'categories']);
    }
}
