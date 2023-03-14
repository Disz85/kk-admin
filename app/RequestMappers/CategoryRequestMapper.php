<?php

namespace App\RequestMappers;

use App\Models\Category;

class CategoryRequestMapper
{
    public function map(Category $category, array $data): Category
    {
        $category->fill([
            'name' => data_get($data, 'name'),
            'description' => data_get($data, 'description'),
            'type' => data_get($data, 'type'),
            'parent_id' => data_get($data, 'parent.id'),
            'is_archived' => data_get($data, 'is_archived'),
        ]);

        $category->save();

        return $category;
    }
}
