<?php

namespace App\Http\Requests\Api;

use App\Enum\CategoryTypeEnum;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductChangeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:products',
            'brand.id' => 'required|integer|exists:brands,id',
            'price' => 'nullable|string',
            'size' => 'nullable|string',
            'description' => 'required|array',
            'image.id' => 'nullable|int|exists:media,id',
            'category.id' => ['required', 'int',
                Rule::exists(Category::class, 'id')->where('type', CategoryTypeEnum::Product->value)],
            'skin_types.*.id' => ['required', 'int',
                Rule::exists(Category::class, 'id')->where('type', CategoryTypeEnum::SkinType->value)],
            'skin_concerns.*.id' => ['required', 'int',
                Rule::exists(Category::class, 'id')->where('type', CategoryTypeEnum::SkinConcern->value)],
            'hair_problems.*.id' => ['required', 'int',
                Rule::exists(Category::class, 'id')->where('type', CategoryTypeEnum::HairProblem->value)],
            'ingredients' => 'nullable|array',
            'ingredients.*' => 'string',
        ];
    }
}
