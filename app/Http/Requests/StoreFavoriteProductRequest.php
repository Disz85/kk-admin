<?php

namespace App\Http\Requests;

use App\Models\FavoriteProduct;
use App\Rules\FavoriteProductsCategoryUnique;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFavoriteProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'product1_id' => 'required|integer|exists:products,id',
            'product2_id' => 'required|integer|exists:products,id',
            'product3_id' => 'required|integer|exists:products,id',
            'categories' => ['array',new FavoriteProductsCategoryUnique($this)],
            'categories.*' => ['integer',Rule::unique('categoryables', 'category_id')->where(function ($query) {
                return $query->where('categoryable_type', FavoriteProduct::class);
            })],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->request->has('categories')) {
            $this->merge([
                'categories' => [],
            ]);
        }
    }
}
