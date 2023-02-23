<?php

namespace App\Http\Actions\Api\Products;

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
        $productCategories = data_get($filters, 'product_categories');
        $brands = data_get($filters, 'brands');
        $ingredients = data_get($filters, 'ingredients');
        $ingredientsToExclude = data_get($filters, 'exclude_ingredients');
        $skinTypes = data_get($filters, 'skin_types');
        $skinConcerns = data_get($filters, 'skin_concerns');
        $hairProblems = data_get($filters, 'hair_problems');

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
                $productCategories,
                fn (BoolQueryBuilder $builder) => $builder
                    ->filter(
                        Query::terms()
                            ->field('category_hierarchy.uuid.keyword')
                            ->values($productCategories)
                    )
            )
            ->when(
                $brands,
                fn (BoolQueryBuilder $builder) => $builder
                    ->filter(
                        Query::terms()
                            ->field('brand.uuid.keyword')
                            ->values($brands)
                    )
            )
            ->when(
                $ingredients,
                fn (BoolQueryBuilder $builder) => $builder
                    ->filter(
                        Query::terms()
                            ->field('ingredients.uuid.keyword')
                            ->values($ingredients)
                    )
            )
            ->when(
                $skinTypes,
                fn (BoolQueryBuilder $builder) => $builder
                    ->filter(
                        Query::terms()
                            ->field('skin_types.uuid.keyword')
                            ->values($skinTypes)
                    )
            )
            ->when(
                $skinConcerns,
                fn (BoolQueryBuilder $builder) => $builder
                    ->filter(
                        Query::terms()
                            ->field('skin_concerns.uuid.keyword')
                            ->values($skinConcerns)
                    )
            )
            ->when(
                $hairProblems,
                fn (BoolQueryBuilder $builder) => $builder
                    ->filter(
                        Query::terms()
                            ->field('hair_problems.uuid.keyword')
                            ->values($hairProblems)
                    )
            )
            ->when(
                $ingredientsToExclude,
                fn (BoolQueryBuilder $builder) => $builder
                    ->mustNot(
                        Query::terms()
                            ->field('ingredients.uuid.keyword')
                            ->values($ingredientsToExclude)
                    )
            );
    }
}
