<?php

namespace App\RequestMappers;

use App\Models\Ingredient;
use App\Models\Media;

class IngredientRequestMapper
{
    public function map(Ingredient $ingredient, array $data): Ingredient
    {
        $ingredient->fill([
            'name' => data_get($data, 'name'),
            'slug' => data_get($data, 'slug'),
            'ewg_data' => data_get($data, 'ewg_data'),
            'ewg_score' => data_get($data, 'ewg_score'),
            'ewg_score_max' => data_get($data, 'ewg_score_max'),
            'comedogen_index' => data_get($data, 'comedogen_index'),
            'description' => data_get($data, 'description'),
            'is_approved' => data_get($data, 'is_approved'),
        ]);

        if (! $ingredient->slug) {
            $ingredient->generateSlug();
        }

        $ingredient->save();

        $ingredient->categories()->sync(data_get($data, 'categories'));

        if (array_key_exists('image_id', $data) && $data['image_id'] !== null) {
            $ingredient->image()->associate(Media::findOrFail($data['image_id']));
        }

        $ingredient->save();

        return $ingredient;
    }
}
