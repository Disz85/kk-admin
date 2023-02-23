<?php

namespace App\Http\Requests;

use App\Rules\CheckDependencies;
use Illuminate\Foundation\Http\FormRequest;

class DeleteBrandRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'brand' => new CheckDependencies($this->brand),
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
            'brand' => $this->brand,
        ]);
    }
}
