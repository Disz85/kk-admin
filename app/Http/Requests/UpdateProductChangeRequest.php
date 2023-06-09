<?php

namespace App\Http\Requests;

use App\Enum\CategoryTypeEnum;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductChangeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string|max:255|unique:products',
            'brand.id' => 'nullable|integer|exists:brands,id',
            'price' => 'nullable|string',
            'size' => 'nullable|string',
            'description' => 'nullable|array',
            'image.id' => 'nullable|int|exists:media,id',
            'category.id' => ['nullable', 'int',
                Rule::exists(Category::class, 'id')->where('type', CategoryTypeEnum::Product->value)],
            'skin_types.*.id' => ['required', 'int',
                Rule::exists(Category::class, 'id')->where('type', CategoryTypeEnum::SkinType->value)],
            'skin_concerns.*.id' => ['required', 'int',
                Rule::exists(Category::class, 'id')->where('type', CategoryTypeEnum::SkinConcern->value)],
            'hair_problems.*.id' => ['required', 'int',
                Rule::exists(Category::class, 'id')->where('type', CategoryTypeEnum::HairProblem->value)],
            'ingredients' => 'nullable|array',
            'ingredients.*' => 'string',
            'where_to_find' => 'nullable|string',
            ];
    }
}
