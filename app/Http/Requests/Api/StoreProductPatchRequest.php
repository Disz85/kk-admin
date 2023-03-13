<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductPatchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'price' => 'nullable|string',
            'size' => 'nullable|string',
            'where_to_find' => 'nullable|string',
            'ingredients' => 'nullable|array',
            'ingredients.*' => 'string',
            'product_id' => 'required|int|exists:products,id',
        ];
    }
}
