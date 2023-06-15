<?php

namespace App\Http\Requests\OurService;

use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name' => ['required'],
            'description' => ['required'],
            'icon' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama layanan harus diisi',
            'description.required' => 'Deskripsi layanan harus diisi',
            'icon.required' => 'Icon layanan harus diisi',
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