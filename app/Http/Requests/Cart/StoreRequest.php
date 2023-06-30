<?php

namespace App\Http\Requests\Cart;

use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class StoreRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                Rule::exists('products', 'id'),
            ],
            'product_name' => [
                'required',
            ],
            'product_price' => [
                'required',
            ],
            'qty' => [
                'required',
                'min:1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Produk harus diisi',
            'product_id.exists' => 'Produk tidak ditemukan',
            'product_name.required' => 'Nama produk harus diisi',
            'product_price.required' => 'Harga produk harus diisi',
            'qty.required' => 'Qty harus diisi',
            'qty.min' => 'Qty minimal 1',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function failedValidation(Validator $validator)
    {
        if (! $this->wantsJson()) {
            $errors = implode('<br>', $validator->errors()->all());
            alert()->html('Gagal',$errors,'error');
            parent::failedValidation($validator);
        }
    }
}
