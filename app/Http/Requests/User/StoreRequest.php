<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\CustomValidationException;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users',
            ],
            'name' => [
                'required',
            ],
            'phone' => [
                'required',
                'numeric',
                'min:8',
                'unique:users,phone',
            ],
            'roles' => [
                'required',
            ],
            'avatar' => [
                'nullable',
                'image',
                'max:2048',
                'mimes:jpeg,bmp,png,gif,svg,jpg',
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8',
            ],
            'email_verified_at' => [
                'date_format:Y-m-d H:i:s'
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
            'avatar.mimes' => 'Foto harus berupa jpeg, bmp, png, gif, svg , jpg',
            'avatar.max' => 'Foto tidak boleh lebih dari 2MB',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Password tidak sama',
            'roles.required' => 'Role tidak boleh kosong',
            'email_verified_at.date_format' => 'Format verifikasi email at tidak sesuai',
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
