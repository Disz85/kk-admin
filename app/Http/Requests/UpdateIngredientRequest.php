<?php

namespace App\Http\Requests;

use App\Enum\IngredientEwgDataEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class UpdateIngredientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|unique:ingredients',
            'ewg_data' => ['nullable', 'string', new Enum(IngredientEwgDataEnum::class)],
            'ewg_score' => 'nullable|integer|between:1,10',
            'ewg_score_max' => ['nullable', 'integer', 'between:1,10', function ($attribute, $value, $fail) {
                if ($value < $this->ewg_score) {
                    $fail('Ewg_score_max must be bigger than ewg_score.');
                }
            }],
            'comedogen_index' => 'nullable|integer|between:0,5',
            'description' => 'nullable|string',
            'image_id' => 'nullable|exists:media,id',
            'is_approved' => 'required|boolean',
            'categories' => 'required|array|min:1',
            'categories.*' => 'required|exists:categories,id',
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'is_approved' => $this->toBoolean($this->get('is_approved', false)),
        ]);
    }

    /**
     * @param $boolable
     * @return bool
     */
    private function toBoolean($boolable = false): bool
    {
        return filter_var($boolable, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
    }

    /**
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
