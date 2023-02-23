<?php

namespace App\Http\Requests\Api;

use App\Traits\ProcessRequestSortValue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductListRequest extends FormRequest
{
    use ProcessRequestSortValue;

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'int'],
            'page' => ['sometimes', 'int'],
            'sort' => ['sometimes', Rule::in(['published_at', '-published_at'])],
            'filter' => ['sometimes', 'array'],
            'filter.name' => ['sometimes', 'string'],
            'filter.product_categories' => ['sometimes', 'array'],
            'filter.brands' => ['sometimes', 'array'],
            'filter.skin_types' => ['sometimes', 'array'],
            'filter.skin_concerns' => ['sometimes', 'array'],
            'filter.hair_problems' => ['sometimes', 'array'],
            'filter.ingredients' => ['sometimes', 'array'],
            'filter.exclude_ingredients' => ['sometimes', 'array'],
        ];
    }
}
