<?php

namespace App\Http\Requests\ProductStock;

use App\Enums\ProductStockEnum;
use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => [
                'required',
                'in:'.implode(",",[ProductStockEnum::TYPE_MASUK,ProductStockEnum::TYPE_KELUAR])
            ],
            'qty' => [
                'required',
                'numeric',
                'min:1'
            ],
            'date' => [
                'required',
                'date',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Jenis input stok harus diisi',
            'type.in' => 'Jenis input stok tidak valid',
            'qty.required' => 'Kuantiti harus diisi',
            'qty.numeric' => 'Kuantiti harus berupa angka',
            'qty.min' => 'Kuantiti minimal 1 angka',
            'date.required' => 'Tanggal inventoris harus diisi',
            'date.date' => 'Tanggal inventoris tidak valid',
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
