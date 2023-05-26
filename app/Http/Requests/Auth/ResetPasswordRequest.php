<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'token' => ['required'],
            'email' => [
                'required',
                'email',
                'exists:users,email',
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'token.required' => 'Token tidak boleh kosong',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Email tidak valid',
            'email.exists' => 'Email tidak ditemukan',
            'password.required' => 'Password tidak boleh kosong',
            'password.confirmed' => 'Password tidak sama',
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
            $this->redirect = route('dashboard.auth.reset-password.index', [
                'token' => $this->route('token'),
                'email' => $this->query('email'),
            ]);
        }
        parent::failedValidation($validator);
    }
}
