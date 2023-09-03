<?php

namespace App\Http\Requests\WhyUs;

use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'title' => ['required'],
            'sub_title' => ['required'],
            'whyus-trixFields.content' => [
                'required',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title harus diisi',
            'sub_title.required' => 'Sub Title harus diisi',
            'whyus-trixFields.content.required' => 'Konten harus diisi',
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