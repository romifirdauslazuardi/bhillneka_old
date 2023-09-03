<?php

namespace App\Http\Requests\Setting;

use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class DashboardSettingRequest extends FormRequest
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
            'logo' => [
                'nullable',
                'image',
                'max:2048',
                'mimes:jpeg,png,jpg,svg',
            ],
            'logo_icon' => [
                'nullable',
                'image',
                'max:2048',
                'mimes:jpeg,png,jpg,svg',
            ],
            'favicon' => [
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
            'title.required' => 'Judul dashboard harus diisi.',
            'title.max' => 'Judul dashboard maksimal 255 karakter.',
            'description.required' => 'Deskripsi harus diisi.',
            'keyword.required' => 'Kata kunci harus diisi.',
            'logo.image' => 'Logo dashboard harus berupa gambar',
            'logo.mimes' => 'Logo dashboard harus berupa jpeg,png,jpg,svg',
            'logo.max' => 'Logo dashboard tidak boleh lebih dari 2MB',
            'logo_icon.image' => 'Logo icon dashboard harus berupa gambar',
            'logo_icon.mimes' => 'Logo icon dashboard harus berupa jpeg,png,jpg,svg',
            'logo_icon.max' => 'Logo icon dashboard tidak boleh lebih dari 2MB',
            'footer.required' => 'Footer dashboard harus diisi.',
            'favicon.image' => 'Favicon dashboard harus berupa gambar',
            'favicon.mimes' => 'Favicon dashboard harus berupa jpeg,png,jpg,svg',
            'favicon.max' => 'Favicon dashboard tidak boleh lebih dari 2MB',
            'footer.required' => 'Footer dashboard harus diisi.',
            'footer.max' => 'Footer dashboard maksimal 255 karakter.',
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
