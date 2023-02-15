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
            'authors' => 'required|array|exists:authors,id',
            'tags' => 'nullable|array|exists:tags,id',
            'categories' => 'nullable|array|exists:categories,id',
            'published_at' => 'nullable|date|date_format:Y-m-d H:i:s',
        ];
    }
}
