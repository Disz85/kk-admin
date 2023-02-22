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
            'title' => 'nullable|string|max:255',
            'name' => 'required|string|max:255|unique:authors,name,'.$this->author->id,
            'email' => 'required|email|max:255|unique:authors,email,'.$this->author->id,
            'description' => 'nullable|string',
            'image.id' => 'int|exists:media,id',
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
            'title.max' => 'A titulus nem lehet hosszabb :max karakternél.',
            'name.required' => 'A név megadása kötelező.',
            'name.max' => 'A név nem lehet hosszabb :max karakternél.',
            'name.unique' => 'A megadott névvel már létezik szerző.',
            'email.required' => 'E-mail cím megadása kötelező.',
            'email.email' => 'Az e-mail cím formátuma nem megfelelő.',
            'email.unique' => 'Ezzel az e-mail címmel már létezik szerző.',
            'image.id.required' => 'Kép feltöltése kötelező.',
        ];
    }
}
