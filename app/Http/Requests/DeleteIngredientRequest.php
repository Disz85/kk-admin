<?php

namespace App\Http\Requests;

use App\Rules\CheckDependencies;
use Illuminate\Foundation\Http\FormRequest;

class DeleteIngredientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'ingredient' => new CheckDependencies($this->ingredient),
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'ingredient' => $this->ingredient,
        ]);
    }
}
