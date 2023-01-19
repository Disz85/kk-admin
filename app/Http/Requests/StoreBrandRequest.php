<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255|unique:brands',
            'url' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'image_id' => 'nullable|integer|exists:media,id',
            'where_to_find' => 'nullable|string',
            'created_by' => 'nullable|integer|exists:users,id',
        ];
    }
}
