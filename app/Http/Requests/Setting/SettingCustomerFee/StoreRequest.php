<?php

namespace App\Http\Requests\Setting\SettingCustomerFee;

use App\Enums\SettingCustomerFeeEnum;
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
                'in:'.implode(",",[SettingCustomerFeeEnum::MARK_KURANG_DARI,SettingCustomerFeeEnum::MARK_LEBIH_DARI])
            ],
            'limit' => [
                'required',
                'numeric',
                'min:1',
            ],
            'type' => [
                'required',
                'in:'.implode(",",[SettingCustomerFeeEnum::TYPE_PERCENTAGE,SettingCustomerFeeEnum::TYPE_FIXED])
            ],
            'value' => [
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
            'limit.numeric' => 'Batas nominal harus berupa angka',
            'limit.min' => 'Batas nominal minimal 1',
            'type.required' => 'Jenis percetage / fixed harus disi',
            'type.in' => 'Jenis percentage / fixed tidak valid',
            'value.required' => 'Nilai fee harus diisi',
            'value.numeric' => 'Nilai fee harus berupa angka',
            'value.min' => 'Nilai fee minimal 0',
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
