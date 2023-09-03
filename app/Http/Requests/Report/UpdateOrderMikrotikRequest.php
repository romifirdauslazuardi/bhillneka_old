<?php

namespace App\Http\Requests\Report;

use App\Enums\OrderMikrotikEnum;
use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\RoleEnum;
use App\Models\OrderMikrotik;
use Auth;

class UpdateOrderMikrotikRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $merge = [];
        
        $result = new OrderMikrotik();
        $result = $result->findOrFail(request()->route()->parameter('id'));

        $merge["type"] = $result->type;
        
        $this->merge($merge);
    }

    public function rules(): array
    {
        return [
            'username' => [
                ($this->type == OrderMikrotikEnum::TYPE_PPPOE) ? "required" : "nullable",
            ],
            'password' => [
                ($this->type == OrderMikrotikEnum::TYPE_PPPOE) ? "required" : "nullable",
            ],
            'service' => [
                ($this->type == OrderMikrotikEnum::TYPE_PPPOE) ? "required" : "nullable",
            ],
            'server' => [
                ($this->type == OrderMikrotikEnum::TYPE_HOTSPOT) ? "required" : "nullable",
            ],
            'profile' => [
                'required',
            ],
            'expired_date' => [
                "nullable",
                "date"
            ],
            'disabled' => [
                'required',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username harus diisi',
            'password.required' => 'Password harus diisi',
            'server.required' => 'Server harus diisi',
            'service.required' => 'Service harus diisi',
            'profile.required' => 'Profile harus diisi',
            'expired_date.date' => 'Tanggal expired mikrotik tidak valid',
            'disabled.required' => 'Status disabled yes/no harus diisi',
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
