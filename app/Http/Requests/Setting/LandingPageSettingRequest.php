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
            'keyword' => [
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
                'mimes:jpeg,png,jpg,svg',
            ],
            'logo_dark' => [
                'nullable',
                'image',
                'max:2048',
                'mimes:jpeg,png,jpg,svg',
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
            'keyword.required' => 'Kata kunci harus diisi.',
            'email.required' => 'Email harus diisi.',
            'phone.required' => 'Phone harus diisi.',
            'location.required' => 'Lokasi harus diisi.',
            'logo.image' => 'Logo harus berupa gambar',
            'logo.mimes' => 'Logo harus berupa jpeg,png,jpg,svg',
            'logo.max' => 'Logo tidak boleh lebih dari 2MB',
            'logo_dark.image' => 'Logo dark harus berupa gambar',
            'logo_dark.mimes' => 'Logo dark harus berupa jpeg,png,jpg,svg',
            'logo_dark.max' => 'Logo dark tidak boleh lebih dari 2MB',
            'favicon.image' => 'Favicon harus berupa gambar',
            'favicon.mimes' => 'Favicon harus berupa jpeg,png,jpg,svg',
            'favicon.max' => 'Favicon tidak boleh lebih dari 2MB',
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
