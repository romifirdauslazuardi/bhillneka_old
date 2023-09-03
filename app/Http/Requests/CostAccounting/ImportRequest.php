<?php

namespace App\Http\Requests\CostAccounting;

use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => ['required', 'mimes:xlsx,csv,xls,xlsm,xlsb,xltx'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'File harus diisi',
            'file.mimes' => 'Format harus xlsx,csv,xls,xlsm,xlsb,xltx',
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
