<?php

namespace App\Http\Requests\Business;

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
            'location' => [
                'required',
            ],
            'category_id' => [
                'required',
                Rule::exists('business_categories', 'id'),
            ],
            'user_id' => [
                'required',
                Rule::exists('users', 'id'),
            ],
            'village_code' => [
                'required',
                Rule::exists('indonesia_villages', 'code'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama bisnis harus diisi',
            'location.required' => 'Lokasi bisnis harus diisi',
            'category_id.required' => 'Kategori harus diisi',
            'category_id.exists' => 'Kategori tidak ditemukan',
            'user_id.required' => 'User harus diisi',
            'user_id.exists' => 'User tidak ditemukan',
            'village_code.required' => 'Desa harus diisi',
            'village_code.exists' => 'Desa tidak ditemukan',
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
