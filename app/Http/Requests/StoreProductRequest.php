<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:products',
            'canonical_name' => 'nullable|string|max:255',
            'price' => 'required|string',
            'size' => 'nullable|string',
            'where_to_find' => 'nullable|string|max:255',
            'description' => 'nullable|array',
            'is_active' => 'required|boolean',
            'is_sponsored' => 'required|boolean',
            'is_18_plus' => 'required|boolean',
            'brand_id' => 'required|integer|exists:brands,id',
            'created_by' => 'nullable|integer|exists:users,id',
            'ingredients_by' => 'nullable|integer|exists:users,id',
            'image_id' => 'required|integer|exists:media,id',
            'tags' => 'nullable|array|exists:tags,id',
            'categories' => 'nullable|array|exists:categories,id',
            'ingredients' => 'nullable|array|exists:ingredients,id',
            'published_at' => 'nullable|date|date_format:Y-m-d H:i:s',
        ];
    }
}
