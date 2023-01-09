<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandChangeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = (new StoreBrandRequest())->rules();
        $rules[ 'title' ] = 'required|string|max:255|unique:brands,title,'.$this->input('brand_id');
        $rules[ 'brand_id' ] = 'nullable|integer|exists:brands,id';
        $rules[ 'created_by' ] = 'required|integer|exists:users,id';

        return $rules;
    }
}
