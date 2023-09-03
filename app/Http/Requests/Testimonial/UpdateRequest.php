<?php

namespace App\Http\Requests\Testimonial;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\CustomValidationException;

class UpdateRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name' => [
                'required',
            ],
            'position' => [
                'required',
            ],
            'message' => [
                'required',
            ],
            'star' => [
                'required',
                'min:1',
                'numeric',
            ],
            'avatar' => [
                'nullable',
                'image',
                'max:2048',
                'mimes:jpeg,png,jpg',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama tidak boleh kosong',
            'position.required' => 'Jabatan tidak boleh kosong',
            'message.required' => 'Pesan tidak boleh kosong',
            'star.required' => 'Jumlah bintang tidak boleh kosong',
            'star.min' => 'Jumlah bintang minimal 1',
            'star.numeric' => 'Jumlah bintang harus berupa angka',
            'avatar.image' => 'Foto harus berupa gambar',
            'avatar.mimes' => 'Foto harus berupa jpeg,png,jpg',
            'avatar.max' => 'Foto tidak boleh lebih dari 2MB',
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
