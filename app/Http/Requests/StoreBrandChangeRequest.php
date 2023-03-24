<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandChangeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = (new StoreBrandRequest())->rules();
        $rules[ 'brand_id' ] = 'nullable|integer|exists:brands,id';
        $rules[ 'created_by' ] = 'required|integer|exists:users,id';

        return $rules;
    }

    /**
     * Get the validation errors.
     *
     * @return string[]
     */
    public function messages(): array
    {
        $messages = (new StoreBrandRequest())->messages();

        $messages['brand_id.exists'] = 'A megadott márka nem létezik.';
        $messages['created_by.required'] = 'A létrehozó megadása kötelező.';
        $messages['created_by.exists'] = 'A megadott létrehozó nem létezik.';

        return $messages;
    }
}
