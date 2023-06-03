<?php

namespace App\Http\Requests\UserBank;

use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\RoleEnum;
use App\Enums\UserBankEnum;
use Auth;

class UpdateRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $merge = [];
        if(Auth::user()->hasRole([RoleEnum::AGEN])){
            $merge["user_id"] = Auth::user()->id;
        }
        if(Auth::user()->hasRole([RoleEnum::ADMIN_AGEN])){
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
            'status' => [
                (Auth::user()->hasRole([RoleEnum::OWNER])) ? "required" : "nullable",
                'in:'.implode(",",[UserBankEnum::STATUS_WAITING_APPROVE,UserBankEnum::STATUS_APPROVED,UserBankEnum::STATUS_REJECTED])
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
            'status.required' => 'Status harus diisi',
            'status.in' => 'Status tidak valid',
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
