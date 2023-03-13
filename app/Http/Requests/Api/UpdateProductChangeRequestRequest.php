<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class UpdateProductChangeRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return($this->product_change_request->user_id == Auth::user()->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if ($this->product_change_request->product) {
            $req = new StoreProductPatchRequest();

            return  Arr::except($req->rules(), 'product_id');
        } else {
            $req = new StoreProductChangeRequest();

            return $req->rules();
        }
    }
}
