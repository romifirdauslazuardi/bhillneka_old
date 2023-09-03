<?php

namespace App\Http\Requests\UserPayLater;

use App\Enums\UserPayLaterEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\CustomValidationException;
use Illuminate\Validation\Rule;
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
            'user_id' => [
                'required',
                Rule::exists('users', 'id'),
            ],
            'business_id' => [
                'required',
                Rule::exists('business', 'id'),
            ],
            'status' => [
                'required',
                'in:'.implode(",",[UserPayLaterEnum::STATUS_TRUE,UserPayLaterEnum::STATUS_FALSE])
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User tidak boleh kosong',
            'user_id.exists' => 'User tidak ditemukan',
            'business_id.required' => 'Bisnis harus diisi',
            'business_id.exists' => 'Bisnis tidak ditemukan',
            'status.required' => 'Status tidak boleh kosong',
            'status.in' => 'Status tidak valid',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function failedValidation(Validator $validator)
    {
        if (! $this->wantsJson()) {
            $errors = implode('<br>', $validator->errors()->all());
            alert()->html('Gagal',$errors,'error');
            parent::failedValidation($validator);
        }
        else{
            throw new CustomValidationException($validator);
        }
    }
}
