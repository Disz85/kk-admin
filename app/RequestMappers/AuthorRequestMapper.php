<?php

namespace App\RequestMappers;

use App\Models\Author;
use App\Models\Media;

class AuthorRequestMapper
{
    public function map(Author $author, array $data): Author
    {
        $author->fill([
            'title' => data_get($data, 'title'),
            'name' => data_get($data, 'name'),
            'slug' => data_get($data, 'slug'),
            'email' => data_get($data, 'email'),
            'description' => data_get($data, 'description'),
        ]);

        if (! $author->slug) {
            $author->generateSlug();
        }

        $author->save();

        if (array_key_exists('image', $data) && array_key_exists('id', $data['image']) && $data['image']['id'] !== null) {
            $author->image()->associate(Media::findOrFail($data['image']['id']));
        }

        $author->save();

        return $author;
    }
}
