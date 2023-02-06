<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductChangeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = (new StoreProductRequest())->rules();
        $rules['ingredients_new'] = 'nullable|array';
        $rules['ingredients_new.*'] = 'string|unique:ingredients,name';
        $rules[ 'product_id' ] = 'nullable|integer|exists:products,id';
        $rules[ 'created_by' ] = 'required|integer|exists:users,id';

        return $rules;
    }
}
