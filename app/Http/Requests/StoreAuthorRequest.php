<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuthorRequest extends FormRequest
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
            'email' => 'required|email|unique:authors',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image_id' => 'nullable|integer|exists:media,id',
        ];
    }
}
