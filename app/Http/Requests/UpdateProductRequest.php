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
            'price' => 'nullable|integer',
            'size' => 'nullable|integer',
            'where_to_find' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'brand_id' => 'nullable|integer',
            'active' => 'required|boolean',
            'hidden' => 'required|boolean',
            'sponsored' => 'required|boolean',
            'is_18_plus' => 'required|boolean',
            'created_by' => 'nullable|integer|exists:users,id',
            'image_id' => 'nullable|integer|exists:media,id',
            'tags' => 'nullable|array|exists:tags,id',
            'categories' => 'nullable|array|exists:categories,id',
            'ingredients' => 'nullable|array|exists:ingredients,id',
            'published_at' => 'nullable|date|date_format:Y-m-d H:i:s',
        ];
    }
}
