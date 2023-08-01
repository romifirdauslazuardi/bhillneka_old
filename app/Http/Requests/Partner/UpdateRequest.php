<?php

namespace App\Http\Requests\Partner;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Exceptions\CustomValidationException;
use Auth;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
            ],
            'image' => [
                'nullable',
                'image',
                'max:2048',
                'mimes:jpeg,bmp,png,gif,svg,jpg',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama mitra tidak boleh kosong',
            'image.image' => 'Gambar harus berupa gambar',
            'image.mimes' => 'Gambar harus berupa jpeg, bmp, png, gif, svg , jpg',
            'image.max' => 'Gambar tidak boleh lebih dari 2MB',
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
