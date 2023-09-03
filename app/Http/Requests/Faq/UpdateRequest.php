<?php

namespace App\Http\Requests\Faq;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\CustomValidationException;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'question' => [
                'required',
            ],
            'answer' => [
                'required',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'question.required' => 'Pertanyaan harus diisi',
            'answer.required' => 'Jawaban harus diisi',
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
