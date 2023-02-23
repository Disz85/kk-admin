<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\QueryBuilder;
use Spatie\QueryBuilder\Filters\Filter;

/**
 * @template T of Model
 * @implements Filter<T>
 */
class DepthFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        /** @var QueryBuilder $query */
        return $query->unless(is_null($value), function (QueryBuilder $builder) use ($value): void {
            // @phpstan-ignore-next-line
            $builder
                ->withDepth()
                ->having('depth', $value);
        });
    }
}
