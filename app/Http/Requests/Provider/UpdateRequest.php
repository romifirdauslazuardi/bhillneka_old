<?php

namespace App\Http\Requests\Provider;

use App\Enums\ProviderEnum;
use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
            ],
            'type' => [
                'required',
                'in:'.implode(",",[ProviderEnum::TYPE_MANUAL_TRANSFER,ProviderEnum::TYPE_DOKU])
            ],
            'status' => [
                'required',
                'in:'.implode(",",[ProviderEnum::STATUS_TRUE,ProviderEnum::STATUS_FALSE])
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama provider harus diisi',
            'status.required' => 'Status provider harus diisi',
            'status.in' => 'Status provider tidak valid',
            'type.required' => 'Tipe provider harus diisi',
            'type.in' => 'Tipe provider tidak valid',
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
