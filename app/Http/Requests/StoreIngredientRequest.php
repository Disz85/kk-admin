<?php

namespace App\Http\Requests;

use App\Enum\IngredientEwgDataEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreIngredientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:ingredients',
            'ewg_data' => ['nullable', 'string', new Enum(IngredientEwgDataEnum::class)],
            'ewg_score' => 'nullable|integer|between:0,10',
            'ewg_score_max' => ['nullable', 'integer', 'between:0,10', function ($attribute, $value, $fail) {
                if ($value < $this->ewg_score) {
                    $fail('Ewg_score_max must be bigger than ewg_score.');
                }
            }],
            'comedogen_index' => 'nullable|integer|between:0,5',
            'description' => 'nullable|array',
            'image_id' => 'nullable|integer|exists:media,id',
            'published_at' => 'nullable|date|date_format:Y-m-d H:i:s',
            'created_by' => 'nullable|integer|exists:users,id',
            'categories' => 'required|array|min:1|exists:categories,id',
        ];
    }
}
