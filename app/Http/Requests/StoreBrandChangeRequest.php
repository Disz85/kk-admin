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
}
