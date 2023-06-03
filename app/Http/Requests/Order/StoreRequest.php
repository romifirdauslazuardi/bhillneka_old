<?php

namespace App\Http\Requests\Order;

use App\Enums\RoleEnum;
use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class StoreRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $merge = [];
        if(Auth::user()->hasRole([RoleEnum::AGEN])){
            $merge["user_id"] = Auth::user()->id;
        }
        if(Auth::user()->hasRole([RoleEnum::ADMIN_AGEN])){
            $merge["user_id"] = Auth::user()->user_id;
        }
        $this->merge($merge);
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                Rule::exists('users', 'id'),
            ],
            'customer_id' => [
                'nullable',
                Rule::exists('users', 'id'),
            ],
            'provider_id' => [
                'required',
                Rule::exists('providers', 'id'),
            ],
            'repeater' => [
                'required',
                'array',
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User harus diisi',
            'user_id.exists' => 'User tidak ditemukan',
            'customer_id.exists' => 'Customer tidak ditemukan',
            'provider_id.required' => 'Provider harus diisi',
            'provider_id.exists' => 'Provider tidak ditemukan',
            'repeater.required' => 'Produk belum dipilih',
            'repeater.array' => 'Produk tidak valid',
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
