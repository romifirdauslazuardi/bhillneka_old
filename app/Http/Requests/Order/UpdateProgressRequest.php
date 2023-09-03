<?php

namespace App\Http\Requests\Order;

use App\Enums\OrderEnum;
use App\Enums\RoleEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\CustomValidationException;
use Illuminate\Validation\Rule;
use Auth;

class UpdateProgressRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'progress' => [
                'required',
                'in:'.implode(",",[OrderEnum::PROGRESS_BATAL,OrderEnum::PROGRESS_DRAFT,OrderEnum::PROGRESS_PENDING,OrderEnum::PROGRESS_DIKONFIRMASI,OrderEnum::PROGRESS_DIKIRIM,OrderEnum::PROGRESS_TERIKIRIM,OrderEnum::PROGRESS_SELESAI])
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'progress.required' => 'Progress order harus diisi',
            'progress.in' => 'Progress order tidak valid',
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
