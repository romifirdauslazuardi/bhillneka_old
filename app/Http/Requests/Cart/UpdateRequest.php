<?php

namespace App\Http\Requests\Cart;

use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class UpdateRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'qty' => [
                'required',
                'min:0',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'qty.required' => 'Qty harus diisi',
            'qty.min' => 'Qty minimal 0',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function failedValidation(Validator $validator)
    {
        if (! $this->wantsJson()) {
            $errors = implode('<br>', $validator->errors()->all());
            alert()->html('Gagal',$errors,'error');
            parent::failedValidation($validator);
        }
    }
}
