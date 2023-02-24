<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:products,name,'.$this->product->id,
            'canonical_name' => 'nullable|string|max:255',
            'price' => 'nullable|string',
            'size' => 'nullable|string',
            'where_to_find' => 'nullable|string|max:255',
            'description' => 'required|array',
            'is_active' => 'required|boolean',
            'is_sponsored' => 'required|boolean',
            'is_18_plus' => 'required|boolean',
            'brand.id' => 'required|integer|exists:brands,id',
            'updated_by' => 'nullable|integer|exists:users,id',
            'ingredients_by' => 'nullable|integer|exists:users,id',
            'image.id' => 'nullable|int|exists:media,id',
            'tags.*.id' => 'nullable|int|exists:tags,id',
            'category.id' => 'required|int|exists:categories,id',
            'skin_types.*.id' => 'nullable|int|exists:categories,id',
            'skin_concerns.*.id' => 'nullable|int|exists:categories,id',
            'ingredients.*.id' => 'nullable|int|exists:ingredients,id',
        ];
    }

    /**
     * Get the validation errors.
     *
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'name.required' => 'A név megadása kötelező.',
            'name.max' => 'A név nem lehet hosszabb :max karakternél.',
            'name.unique' => 'A megadott névvel már létezik termék.',
            'canonical_name.max' => 'A canonical név nem lehet hosszabb :max karakternél.',
            'description.required' => 'A leírás kitöltése kötelező.',
            'where_to_find.max' => 'A lelőhely nem lehet hosszabb :max karakternél.',
            'brand.id.required' => 'A márka megadása kötelező.',
            'category.id.required' => 'Kategória megadása kötelező.',
        ];
    }
}
