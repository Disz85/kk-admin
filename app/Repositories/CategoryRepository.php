<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    /**
     * @param string $type
     * @return object|null
     */
    public static function getCategoriesByType(string $type): ?object
    {
        return Category::where('type', $type)->get();
    }
}
