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
            'description' => 'nullable|array',
            'image.id' => 'required|int|exists:media,id',
            'where_to_find' => 'nullable|string',
            'created_by' => 'nullable|integer|exists:users,id',
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
            'title.required' => 'A név megadása kötelező.',
            'title.max' => 'A név nem lehet hosszabb :max karakternél.',
            'title.unique' => 'A megadott névvel már létezik márka.',
            'url.url' => 'Az url formátuma nem valid. Használja a https://valami.hu alakot.',
            'url.max' => 'Az url nem lehet hosszabb :max karakternél.',
            'image.id.required' => 'Kép feltöltése kötelező.',
        ];
    }
}
