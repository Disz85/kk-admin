<?php

namespace App\RequestMappers;

use App\Models\Tag;

class TagRequestMapper
{
    /**
     * @param Tag $tag
     * @param array $data
     * @return Tag
     */
    public function map(Tag $tag, array $data): Tag
    {
        $tag->fill([
            'name' => data_get($data, 'name'),
            'description' => data_get($data, 'description'),
            'is_highlighted' => data_get($data, 'is_highlighted'),
        ]);

        $tag->save();

        return $tag;
    }
}
