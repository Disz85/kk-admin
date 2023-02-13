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
            'name' => 'required|string|unique:tags,name,'.$this->tag->id,
            'description' => 'nullable|string',
            'is_highlighted' => 'sometimes|boolean',
        ];
    }
}
