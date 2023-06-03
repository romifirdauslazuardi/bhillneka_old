<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users','email')->whereNull('deleted_at'),
            ],
            'password' => [
                'required',
                'min:8',
                'confirmed',
            ],
            'phone' => [
                'required',
                'min:8',
                'numeric',
                Rule::unique('users','phone')->whereNull('deleted_at'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.required' => 'Phone harus diisi',
            'password.confirmed' => 'Password tidak sesuai',
            'phone.min' => 'Phone minimal 8 karakter',
            'phone.numeric' => 'Phone harus berupa angka',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function failedValidation(Validator $validator)
    {
        if (!$this->wantsJson()) {
            $errors = implode('<br>', $validator->errors()->all());
            alert()->html('Gagal', $errors, 'error');
            $this->redirect = route("dashboard.auth.register.index");
        }

        parent::failedValidation($validator);
    }
}
