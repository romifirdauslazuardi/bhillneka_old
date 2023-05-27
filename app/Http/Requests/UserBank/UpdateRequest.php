<?php

namespace App\Http\Requests\UserBank;

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
            'number' => [
                'required',
                'numeric',
                'min:1'
            ],
            'bank_id' => [
                'required',
                Rule::exists('banks', 'id'),
            ],
            'user_id' => [
                'required',
                Rule::exists('users', 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Atas nama rekening harus diisi',
            'bank_id.required' => 'Bank harus diisi',
            'bank_id.exists' => 'Bank tidak ditemukan',
            'user_id.required' => 'User harus diisi',
            'user_id.exists' => 'User tidak ditemukan',
            'number.required' => 'Nomor rekening harus diisi',
            'number.numeric' => 'Nomor rekening harus berupa angka',
            'number.min' => 'Nomor rekening minimal 1 angka',
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
