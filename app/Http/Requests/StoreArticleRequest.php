<?php

namespace App\Http\Requests;

use App\Models\Author;
use App\Models\Category;
use App\Models\Media;
use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreArticleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255|unique:articles',
            'lead' => 'nullable|string|max:255',
            'body' => 'required|string',
            'active' => 'sometimes|boolean',
            'hidden' => 'sometimes|boolean',
            'sponsored' => 'sometimes|boolean',
            'is_18_plus' => 'sometimes|boolean',

            'image_id' => [
                'nullable',
                'integer',
                Rule::exists(Media::class, 'id'),
            ],

            'authors' => 'required|array',
            'authors.*' => [
                'distinct',
                'integer',
                Rule::exists(Author::class, 'id'),
            ],

            'tags' => 'nullable|array',
            'tags.*' => [
                'nullable',
                'distinct',
                'integer',
                Rule::exists(Tag::class, 'id'),
            ],

            'categories' => 'nullable|array',
            'categories.*' => [
                'nullable',
                'distinct',
                'integer',
                Rule::exists(Category::class, 'id'),
            ],

            'published_at' => 'nullable|date|date_format:Y-m-d H:i:s',
        ];
    }
}
