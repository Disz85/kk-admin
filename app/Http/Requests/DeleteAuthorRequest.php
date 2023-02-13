<?php

namespace App\Http\Requests;

use App\Rules\CheckDependencies;
use Illuminate\Foundation\Http\FormRequest;

class DeleteAuthorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'author' => new CheckDependencies($this->author),
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
            'author' => $this->author,
        ]);
    }
}
