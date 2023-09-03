<?php

namespace App\Http\Requests\Order;

use App\Enums\OrderEnum;
use App\Enums\RoleEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\CustomValidationException;
use Illuminate\Validation\Rule;
use Auth;

class UpdateStatusRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'in:'.implode(",",[OrderEnum::STATUS_EXPIRED,OrderEnum::STATUS_FAILED,OrderEnum::STATUS_PENDING,OrderEnum::STATUS_REDIRECT,OrderEnum::STATUS_REFUNDED,OrderEnum::STATUS_SUCCESS,OrderEnum::STATUS_TIMEOUT,OrderEnum::STATUS_WAITING_PAYMENT])
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status order harus diisi',
            'status.in' => 'Status order tidak valid',
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
