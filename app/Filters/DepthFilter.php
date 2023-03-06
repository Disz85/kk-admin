<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Kalnoy\Nestedset\QueryBuilder;
use Spatie\QueryBuilder\Filters\Filter;

class DepthFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        return $query->unless(is_null($value), function (QueryBuilder $builder) use ($value) {
            $builder
                ->withDepth()
                ->having('depth', $value);
        });
    }
}
