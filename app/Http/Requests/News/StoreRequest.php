<?php

namespace App\Http\Requests\News;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\CustomValidationException;
use Illuminate\Validation\Rule;
use Auth;

class StoreRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $merge = [];
        $merge["user_id"] = Auth::user()->business->user_id ?? null;
        $merge["business_id"] = Auth::user()->business_id;

        $this->merge($merge);
    }
    
    public function rules(): array
    {
        return [
            'title' => [
                'required',
            ],
            'note' => [
                'required',
            ],
            'user_id' => [
                'required',
                Rule::exists('users', 'id'),
            ],
            'customer_id' => [
                'required',
                'array',
            ],
            'customer_id.*' => [
                'required',
                Rule::exists('users', 'id'),
            ],
            'business_id' => [
                'required',
                Rule::exists('business', 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul news harus diisi',
            'note.required' => 'Pesan news harus diisi',
            'user_id.required' => 'User tidak boleh kosong',
            'user_id.exists' => 'User tidak ditemukan',
            'customer_id.required' => 'Customer tidak boleh kosong',
            'customer_id.array' => 'Customer tidak valid',
            'customer_id.*.required' => 'Customer tidak boleh kosong',
            'customer_id.*.exists' => 'Customer tidak ditemukan',
            'business_id.required' => 'Bisnis harus diisi',
            'business_id.exists' => 'Bisnis tidak ditemukan',
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
