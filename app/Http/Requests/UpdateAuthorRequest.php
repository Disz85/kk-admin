<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAuthorRequest extends FormRequest
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
            'email' => 'required|email|max:255|unique:authors,email,'.$this->author->id,
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image.id' => 'int|exists:media,id',
        ];
    }
}
