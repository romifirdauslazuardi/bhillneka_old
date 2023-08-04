<?php

namespace App\Http\Requests\Setting\SettingFee;

use App\Enums\SettingFeeEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\CustomValidationException;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mark' => [
                'required',
                'in:'.implode(",",[SettingFeeEnum::MARK_KURANG_DARI,SettingFeeEnum::MARK_LEBIH_DARI])
            ],
            'limit' => [
                'required',
            ],
            'owner_fee' => [
                'required',
                'numeric',
                'min:0'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'mark.required' => 'Tanda lebih dari / kurang dari harus diisi',
            'mark.in' => 'Tanda lebih dari / kurang dari tidak valid',
            'limit.required' => 'Batas nominal harus diisi',
            'owner_fee.required' => 'Fee owner harus diisi',
            'owner_fee.numeric' => 'Fee owner harus berupa angka',
            'owner_fee.min' => 'Fee owner minimal 0',
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
