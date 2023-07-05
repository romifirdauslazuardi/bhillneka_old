<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\CustomValidationException;
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
        if(Auth::user()->hasRole([RoleEnum::ADMIN_AGEN])){
            $merge["user_id"] = Auth::user()->user_id;
            $merge["roles"] = [RoleEnum::CUSTOMER];
        }
        
        $this->merge($merge);

        if($this->roles == RoleEnum::CUSTOMER){
            if(Auth::user()->hasRole([RoleEnum::AGEN,RoleEnum::ADMIN_AGEN])){
                $this->merge(["business_id" => Auth::user()->business_id]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore(request()->route()->parameter('id'))->whereNull('deleted_at'),
            ],
            'name' => [
                'required',
            ],
            'phone' => [
                'required',
                'numeric',
                'min:8',
                Rule::unique('users','phone')->ignore(request()->route()->parameter('id'))->whereNull('deleted_at'),
            ],
            'roles' => [
                'required',
            ],
            'avatar' => [
                'nullable',
                'image',
                'max:2048',
                'mimes:jpeg,png,jpg',
            ],
            'password' => [
                'nullable',
                'confirmed',
                'min:8',
            ],
            'email_verified_at' => [
                'date_format:Y-m-d H:i:s'
            ],
            'user_id' => [
                (in_array($this->roles,[RoleEnum::CUSTOMER])) ? "required" : "nullable",
                Rule::exists('users', 'id'),
            ],
            'business_id' => [
                (in_array($this->roles,[RoleEnum::CUSTOMER])) ? "required" : "nullable",
                Rule::exists('business', 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama tidak boleh kosong',
            'name.string' => 'Nama harus berupa string',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter',
            'phone.required' => 'Phone tidak boleh kosong',
            'phone.numeric' => 'Phone harus berupa angka',
            'phone.min' => 'Phone minimal 8 angka',
            'phone.unique' => 'Phone sudah terdaftar',
            'email.required' => 'Email tidak boleh kosong',
            'email.string' => 'Email harus berupa string',
            'email.email' => 'Email harus berupa email',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter',
            'email.unique' => 'Email sudah terdaftar',
            'avatar.image' => 'Foto harus berupa gambar',
            'avatar.mimes' => 'Foto harus berupa jpeg, png , jpg',
            'avatar.max' => 'Foto tidak boleh lebih dari 2MB',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Password tidak sama',
            'roles.required' => 'Role tidak boleh kosong',
            'email_verified_at.date_format' => 'Format verifikasi email at tidak sesuai',
            'user_id.required' => 'User harus diisi',
            'user_id.exists' => 'User tidak ditemukan',
            'business_id.required' => 'Business harus diisi',
            'business_id.exists' => 'Business tidak ditemukan',
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
