<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTagRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:tags,name,'.$this->tag->id,
            'description' => 'nullable|string',
            'is_highlighted' => 'sometimes|boolean',
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
            'name.unique' => 'A megadott névvel már létezik címke.',
        ];
    }
}
