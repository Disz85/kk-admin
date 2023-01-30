<?php

namespace App\Http\Requests;

use App\Enum\CategoryTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|integer|exists:categories,id',
            'type' => [
                'required',
                'string',
                new Enum(CategoryTypeEnum::class),
            ],
        ];
    }
}
