<?php

namespace App\Http\Requests\ProductStock;

use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                Rule::exists('products', 'id'),
            ],
            'qty' => [
                'required',
                'numeric',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Produk harus diisi',
            'product_id.exists' => 'Produk tidak ditemukan',
            'qty.required' => 'Kuantiti harus diisi',
            'qty.numeric' => 'Kuantiti harus berupa angka',
            'qty.min' => 'Kuantiti minimal 1 angka',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function failedValidation(Validator $validator)
    {
        throw new CustomValidationException($validator);
    }
}
