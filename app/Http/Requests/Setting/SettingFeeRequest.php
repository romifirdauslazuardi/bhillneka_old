<?php

namespace App\Http\Requests\Setting;

use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SettingFeeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'owner_fee' => [
                'required',
                'min:1',
            ],
            'agen_fee' => [
                'required',
                'min:1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'owner_fee.required' => 'Fee owner harus diisi.',
            'owner_fee.max' => 'Fee owner minimal 1.',
            'agen_fee.required' => 'Fee agen harus diisi.',
            'agen_fee.max' => 'Fee agen minimal 1.',
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
