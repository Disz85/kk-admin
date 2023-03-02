<?php

namespace App\Http\Requests\Api;

use App\Traits\ProcessRequestSortValue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductListRequest extends FormRequest
{
    use ProcessRequestSortValue;

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
            'filter.skin_types' => ['sometimes', 'string'],
            'filter.skin_concerns' => ['sometimes', 'string'],
            'filter.ingredients' => ['sometimes', 'array'],
            'filter.exclude_ingredients' => ['sometimes', 'array'],
        ];
    }
}
