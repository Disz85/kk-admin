<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:brands,title,'.$this->brand->id,
            'url' => 'nullable|url|max:255',
            'description' => 'nullable|array',
            'image.id' => 'int|exists:media,id',
            'where_to_find' => 'nullable|string',
            'approved' => 'sometimes|boolean',
            'updated_by' => 'nullable|integer|exists:users,id',
        ];
    }
}
