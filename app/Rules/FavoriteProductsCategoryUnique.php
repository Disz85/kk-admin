<?php

namespace App\Rules;

use App\Models\FavoriteProduct;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class FavoriteProductsCategoryUnique implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(private Request $request)
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (empty($this->request->categories)) {
            if ($this->request->favorite_product) {
                return FavoriteProduct::where('id', '!=', $this->request->favorite_product->id)->doesntHave('categories')->count() == 0;
            } else {
                return FavoriteProduct::doesntHave('categories')->count() == 0;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Only one default Favorite product group can be exists';
    }
}
