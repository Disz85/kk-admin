<?php

namespace App\Http\Requests;

use App\Enum\CategoryTypeEnum;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string[]>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:products,name,'.$this->product->id,
            'canonical_name' => 'nullable|string|max:255',
            'price' => 'nullable|string',
            'size' => 'nullable|string',
            'where_to_find' => 'nullable|string|max:255',
            'description' => 'required|array',
            'is_active' => 'required|boolean',
            'is_sponsored' => 'required|boolean',
            'is_18_plus' => 'required|boolean',
            'brand.id' => 'required|integer|exists:brands,id',
            'updated_by' => 'nullable|integer|exists:users,id',
            'ingredients_by' => 'nullable|integer|exists:users,id',
            'image.id' => 'nullable|int|exists:media,id',
            'tags.*.id' => 'nullable|int|exists:tags,id',
            'category.id' => ['required', 'int',
                Rule::exists(Category::class, 'id')->where('type', CategoryTypeEnum::Product->value)],
            'skin_types.*.id' => ['required', 'int',
                Rule::exists(Category::class, 'id')->where('type', CategoryTypeEnum::SkinType->value)],
            'skin_concerns.*.id' => ['required', 'int',
                Rule::exists(Category::class, 'id')->where('type', CategoryTypeEnum::SkinConcern->value)],
            'hair_problems.*.id' => ['required', 'int',
                Rule::exists(Category::class, 'id')->where('type', CategoryTypeEnum::HairProblem->value)],
            'ingredients.*.id' => 'nullable|int|exists:ingredients,id',
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
            'name.unique' => 'A megadott névvel már létezik termék.',
            'canonical_name.max' => 'A canonical név nem lehet hosszabb :max karakternél.',
            'image.exists' => 'A kiválasztott kép nem létezik.',
            'description.required' => 'A leírás kitöltése kötelező.',
            'where_to_find.max' => 'A lelőhely nem lehet hosszabb :max karakternél.',
            'brand.id.required' => 'A márka megadása kötelező.',
            'brand.exists' => 'A kiválasztott márka nem létezik.',
            'category.id.required' => 'Kategória megadása kötelező.',
            'category.exists' => 'A kiválasztott kategóriák között van olyan, ami nem létezik.',
            'skin_types.exists' => 'A kiválasztott bőrtípusok között van olyan, ami nem létezik.',
            'skin_concerns.exists' => 'A kiválasztott bőrproblémák között van olyan, ami nem létezik.',
            'hair_problems.exists' => 'A kiválasztott hajproblémák között van olyan, ami nem létezik.',
            'ingredients.exists' => 'A kiválasztott összetevők között van olyan, ami nem létezik.',
            'tags.exists' => 'A kiválasztott címkék között van olyan, ami nem létezik.',
        ];
    }
}
