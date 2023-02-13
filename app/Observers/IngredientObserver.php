<?php

namespace App\Observers;

use App\Models\Ingredient;

class IngredientObserver
{
    /**
     * Handle the Ingredient "deleting" event.
     *
     * @param Ingredient $ingredient
     * @return void
     */
    public function deleting(Ingredient $ingredient)
    {
        $ingredient->image()->delete();
        $ingredient->categories()->detach();
    }
}
