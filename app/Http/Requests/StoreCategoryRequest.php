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
            'name' => 'required|max:512|string',
            'description' => 'nullable|array',
            'type' => ['required', 'string', new Enum(CategoryTypeEnum::class)],
            'parent' => 'nullable|array',
            'parent.type' => ['required_with:parent', 'string', function ($attribute, $value, $fail) {
                if ($value != $this->type) {
                    $fail('A szülő típusa nem egyezik meg az aktuális kategóriáéval.');
                }
            }],
            'parent.id' => 'required_with:parent|integer|exists:categories,id',
            'is_archived' => 'sometimes|boolean',
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
            'type.required' => 'A kategória típusának megadása kötelező.',
        ];
    }
}
