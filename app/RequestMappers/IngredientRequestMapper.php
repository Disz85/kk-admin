<?php

namespace App\RequestMappers;

use App\Models\Ingredient;

class IngredientRequestMapper
{
    /**
     * @param Ingredient $ingredient
     * @param array $data
     * @return Ingredient
     */
    public function map(Ingredient $ingredient, array $data): Ingredient
    {
        $ingredient->fill([
            'name' => data_get($data, 'name'),
            'ewg_data' => data_get($data, 'ewg_data'),
            'ewg_score' => data_get($data, 'ewg_score'),
            'ewg_score_max' => data_get($data, 'ewg_score_max'),
            'comedogen_index' => data_get($data, 'comedogen_index'),
            'description' => data_get($data, 'description'),
            'published_at' => data_get($data, 'published_at'),
            'created_by' => data_get($data, 'created_by'),
        ]);

        $ingredient->save();

        if ($categories = data_get($data, 'categories.*.id')) {
            $ingredient->categories()->sync($categories);
        }

        $ingredient->refresh();

        $ingredient->load('categories');

        return $ingredient;
    }
}
