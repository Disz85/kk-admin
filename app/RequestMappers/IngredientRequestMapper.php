<?php

namespace App\RequestMappers;

use App\Models\Ingredient;
use App\Models\Media;

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

       if (data_get($data, 'image.id')) {
            $ingredient->image()->associate(Media::findOrFail($data['image']['id']));
        }

        $ingredient->save();

        $ingredient->categories()->sync(data_get($data, 'categories'));

        $ingredient->refresh();

        $ingredient->load('categories');

        return $ingredient;
    }
}
