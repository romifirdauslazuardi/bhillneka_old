<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'exists:users,email',
            ],
            'password' => [
                'required',
                'min:8',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.exists' => 'Email tidak ditemukan',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
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
            $this->redirect = route("dashboard.auth.login.index");
        }

        parent::failedValidation($validator);
    }
}
