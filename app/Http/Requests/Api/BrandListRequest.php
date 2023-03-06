<?php

namespace App\Http\Requests\Api;

use App\Traits\ProcessRequestSortValue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandListRequest extends FormRequest
{
    use ProcessRequestSortValue;

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'int'],
            'page' => ['sometimes', 'int'],
            'sort' => ['sometimes', Rule::in(['created_at', '-created_at'])],
            'filter' => ['sometimes', 'array'],
            'filter.name' => ['sometimes', 'string'],
            'filter.abc' => ['sometimes', 'array'],
            'filter.abc.*' => ['string', 'size:1'],
        ];
    }
}
