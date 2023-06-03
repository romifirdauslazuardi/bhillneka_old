<?php

namespace App\Http\Requests\Order;

use App\Enums\OrderEnum;
use App\Enums\RoleEnum;
use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class UpdateRequest extends FormRequest
{

    public function rules(): array
    {
        return [
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
            ],
            'status' => [
                'required',
                'in:'.implode(",",[OrderEnum::STATUS_EXPIRED,OrderEnum::STATUS_FAILED,OrderEnum::STATUS_PENDING,OrderEnum::STATUS_REDIRECT,OrderEnum::STATUS_REFUNDED,OrderEnum::STATUS_SUCCESS,OrderEnum::STATUS_TIMEOUT,OrderEnum::STATUS_WAITING_PAYMENT])
            ]
        ];
    }

    public function messages(): array
    {
        return [
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
