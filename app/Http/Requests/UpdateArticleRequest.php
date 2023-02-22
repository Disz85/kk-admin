<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255|unique:articles,title,'.$this->article->id,
            'lead' => 'nullable|string|max:255',
            'body' => 'required|array',
            'is_active' => 'sometimes|boolean',
            'is_sponsored' => 'sometimes|boolean',
            'is_18_plus' => 'sometimes|boolean',
            'image.id' => 'required|int|exists:media,id',
            'authors' => 'required|array',
            'authors.*.id' => 'required|int|exists:authors,id',
            'tags.*.id' => 'nullable|int|exists:tags,id',
            'categories.*.id' => 'nullable|int|exists:categories,id',
            'published_at' => 'nullable|date|date_format:Y-m-d H:i:s',
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
            'title.required' => 'A cím megadása kötelező.',
            'title.max' => 'A cím nem lehet hosszabb :max karakternél.',
            'title.unique' => 'A megadott címmel már létezik cikk.',
            'lead.max' => 'A lead nem lehet hosszabb :max karakternél.',
            'body.required' => 'A szövegtörzs kitöltése kötelező.',
            'image.id.required' => 'Kép feltöltése kötelező.',
            'authors.required' => 'Szerző megadása kötelező.',
        ];
    }
}
