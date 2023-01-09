<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductChangeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = (new StoreProductRequest())->rules();
        $rules[ 'name' ] = 'required|string|max:255|unique:products,name,'.$this->input('product_id');
        $rules[ 'product_id' ] = 'nullable|integer|exists:products,id';
        $rules[ 'created_by' ] = 'required|integer|exists:users,id';

        return $rules;
    }
}
