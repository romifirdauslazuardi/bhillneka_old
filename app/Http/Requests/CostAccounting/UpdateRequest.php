<?php

namespace App\Http\Requests\CostAccounting;

use App\Enums\CostAccountingEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\CustomValidationException;
use Illuminate\Validation\Rule;
use Auth;

class UpdateRequest extends FormRequest
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
            'type' => [
                'required',
                'in:'.implode(",",[CostAccountingEnum::TYPE_PEMASUKAN,CostAccountingEnum::TYPE_PENGELUARAN])
            ],
            'name' => [
                'required',
            ],
            'nominal' => [
                'required',
            ],
            'date' => [
                'required',
                'date',
            ],
            'user_id' => [
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
            'type.required' => 'Tipe akuntansi harus diisi',
            'type.in' => 'Tipe akuntansi tidak valid',
            'name.required' => 'Nama kegiatan harus diisi',
            'nominal.required' => 'Nominal akuntansi harus diisi',
            'date.required' => 'Tanggal akuntansi harus diisi',
            'date.date' => 'Tanggal akuntansi tidak valid',
            'user_id.required' => 'User tidak boleh kosong',
            'user_id.exists' => 'User tidak ditemukan',
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
