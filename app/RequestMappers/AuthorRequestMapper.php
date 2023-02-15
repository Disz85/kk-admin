<?php

namespace App\RequestMappers;

use App\Models\Author;
use App\Models\Media;

class AuthorRequestMapper
{
    /**
     * @param Author $author
     * @param array $data
     * @return Author
     */
    public function map(Author $author, array $data): Author
    {
        $author->fill([
            'title' => data_get($data, 'title'),
            'name' => data_get($data, 'name'),
            'email' => data_get($data, 'email'),
            'description' => data_get($data, 'description'),
        ]);

       if (data_get($data, 'image.id')) {
            $author->image()->associate(Media::findOrFail($data['image']['id']));
        }

        $author->save();

        $author->load('articles');

        return $author;
    }
}
