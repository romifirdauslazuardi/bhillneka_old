<?php

namespace App\Http\Requests\Unit;

use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\RoleEnum;
use Auth;

class UpdateRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $merge = [];
        if(Auth::user()->hasRole([RoleEnum::AGEN])){
            $merge["user_id"] = Auth::user()->id;
        }
        else if(Auth::user()->hasRole([RoleEnum::ADMIN_AGEN])){
            $merge["user_id"] = Auth::user()->user_id;
        }
        $this->merge($merge);
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
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
            'name.required' => 'Nama unit harus diisi',
            'user_id.required' => 'User harus diisi',
            'user_id.exists' => 'User tidak ditemukan',
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
