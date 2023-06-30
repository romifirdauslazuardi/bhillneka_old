<?php

namespace App\Http\Requests\Profile;

use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\RoleEnum;
use Auth;

class UpdateBusinessPageRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'business_id' => [
                'nullable',
                Rule::exists('business', 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'business_id.exists' => 'Bisnis tidak ditemukan',
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
