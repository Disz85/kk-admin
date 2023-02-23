<?php

namespace App\Filters;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\Filters\Filter;

/**
 * @template T of Model
 * @implements Filter<T>
 */
class ParentIdFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
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
