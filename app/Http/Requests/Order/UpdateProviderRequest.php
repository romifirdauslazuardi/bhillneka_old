<?php

namespace App\Http\Requests\Order;

use App\Enums\BusinessCategoryEnum;
use App\Enums\RoleEnum;
use App\Enums\OrderEnum;
use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class UpdateProviderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'provider_id' => [
                'required',
                Rule::exists('providers', 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'provider_id.required' => 'Provider harus diisi',
            'provider_id.exists' => 'Provider tidak ditemukan',
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
