<?php

namespace App\Http\Requests\Setting;

use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class LandingPageSettingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'max:255',
            ],
            'description' => [
                'required'
            ],
            'email' => [
                'required'
            ],
            'description' => [
                'required'
            ],
            'phone' => [
                'required'
            ],
            'instagram' => [
                'nullable'
            ],
            'facebook' => [
                'nullable'
            ],
            'twitter' => [
                'nullable'
            ],
            'logo' => [
                'nullable',
                'image',
                'max:2048',
                'mimes:jpeg,png,jpg',
            ],
            'logo_dark' => [
                'nullable',
                'image',
                'max:2048',
                'mimes:jpeg,png,jpg',
            ],
            'footer' => [
                'required',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul harus diisi.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'description.required' => 'Deskripsi harus diisi.',
            'email.required' => 'Email harus diisi.',
            'phone.required' => 'Phone harus diisi.',
            'location.required' => 'Lokasi harus diisi.',
            'logo.image' => 'Logo harus berupa gambar',
            'logo.mimes' => 'Logo harus berupa jpeg,png,jpg',
            'logo.max' => 'Logo tidak boleh lebih dari 2MB',
            'logo.image' => 'Logo dark harus berupa gambar',
            'logo.mimes' => 'Logo dark harus berupa jpeg,png,jpg',
            'logo.max' => 'Logo dark tidak boleh lebih dari 2MB',
            'footer.required' => 'Footer harus diisi.',
            'footer.max' => 'Footer maksimal 255 karakter.',
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
