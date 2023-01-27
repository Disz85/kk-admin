<?php

namespace App\Filters;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class ParentIdFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $parentId = Category::query()
            ->where('uuid', '=', $value)
            ->first()
            ?->id;

        $query->when(
            $parentId === null,
            fn ($query) => $query->whereNull($property),
            fn ($query) => $query->where($property, $parentId)
        );
    }
}
