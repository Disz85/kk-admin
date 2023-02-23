<?php

namespace App\Http\Actions\Api\Ingredients;

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
        $ewgScore = data_get($filters, 'ewg_score');
        $categories = data_get($filters, 'categories');

        return Query::bool()
            ->when(
                $name,
                fn (BoolQueryBuilder $builder) => $builder
                    ->filter(
                        Query::match()
                            ->field('name')
                            ->query($name)
                    )
            )
            ->when(
                $abc,
                fn (BoolQueryBuilder $builder) => $builder
                    ->filter(
                        Query::regexp()
                            ->field('name')
                            ->value('[' . implode('', $abc) . '].*')
                            ->caseInsensitive(true)
                    )
            )
            ->when(
                $ewgScore,
                fn (BoolQueryBuilder $builder) => $builder
                    ->filter(
                        Query::term()
                            ->field('ewg_score')
                            ->value($ewgScore)
                    )
            )
            ->when(
                $categories,
                fn (BoolQueryBuilder $builder) => $builder
                    ->filter(
                        Query::terms()
                            ->field('categories.uuid.keyword')
                            ->values($categories)
                    )
            );
    }
}
