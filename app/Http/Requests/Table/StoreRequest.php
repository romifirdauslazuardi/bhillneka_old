<?php

namespace App\Http\Requests\Table;

use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\RoleEnum;
use Auth;

class StoreRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $merge = [];
        $merge["user_id"] = Auth::user()->business->user_id ?? null;
        $merge["business_id"] = Auth::user()->business_id; 
        
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
            'business_id' => [
                'required',
                Rule::exists('business', 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nomor meja harus diisi',
            'user_id.required' => 'User harus diisi',
            'user_id.exists' => 'User tidak ditemukan',
            'business_id.required' => 'Bisnis harus diisi',
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
