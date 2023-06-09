<?php

namespace App\Http\Actions\Api\Brands;

use Elastic\ScoutDriverPlus\Builders\AbstractParameterizedQueryBuilder;
use Elastic\ScoutDriverPlus\Builders\BoolQueryBuilder;
use Elastic\ScoutDriverPlus\Support\Query;

class FilterAction
{
    /**
     * @param array<string, mixed> $filters
     * @return AbstractParameterizedQueryBuilder
     */
    public function __invoke(array $filters): AbstractParameterizedQueryBuilder
    {
        $name = data_get($filters, 'name');
        $abc = data_get($filters, 'abc');

        return Query::bool()
            ->when(
                $name,
                fn (BoolQueryBuilder $builder) => $builder
                    ->filter(
                        Query::match()
                            ->field('title')
                            ->query($name)
                    )
            )
            ->when(
                $abc,
                fn (BoolQueryBuilder $builder) => $builder
                    ->filter(
                        Query::regexp()
                            ->field('title.keyword')
                            ->value('[' . implode('', $abc) . '].*')
                            ->caseInsensitive(true)
                    )
            );
    }
}
