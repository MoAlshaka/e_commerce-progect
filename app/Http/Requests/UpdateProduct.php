<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProduct extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'=>'required|max:225',
            'desc'=>'required',
            'image'=>'nullable|mimes:png,jpg,jpeg|size:50MB',
            'original_price'=>'required|numeric',
            'price_after_descout'=>'nullable|numeric',
            'stock'=>'required|numeric',
            'tag'=>'required',
         ];
    }
}
