<?php

namespace App\Http\Requests\Order;

use App\Enums\BusinessCategoryEnum;
use App\Enums\OrderEnum;
use App\Enums\RoleEnum;
use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class UpdateRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $merge = [];
        $merge["user_id"] = Auth::user()->business->user_id ?? null;
        $merge["business_id"] = Auth::user()->business_id;

        if(!in_array(Auth::user()->business->category->name,[BusinessCategoryEnum::FNB])){
            $merge["fnb_type"] = OrderEnum::FNB_NONE;
        }

        $this->merge($merge);
    }
    
    public function rules(): array
    {
        return [
            'customer_id' => [
                'nullable',
                Rule::exists('users', 'id'),
            ],
            'provider_id' => [
                'required',
                Rule::exists('providers', 'id'),
            ],
            'type' => [
                'required',
                'in:'.implode(",",[OrderEnum::TYPE_ON_TIME_PAY,OrderEnum::TYPE_DUE_DATE])
            ],
            'repeater' => [
                'required',
                'array',
            ],
            'status' => [
                'required',
                'in:'.implode(",",[OrderEnum::STATUS_EXPIRED,OrderEnum::STATUS_FAILED,OrderEnum::STATUS_PENDING,OrderEnum::STATUS_REDIRECT,OrderEnum::STATUS_REFUNDED,OrderEnum::STATUS_SUCCESS,OrderEnum::STATUS_TIMEOUT,OrderEnum::STATUS_WAITING_PAYMENT])
            ],
            'business_id' => [
                'required',
                Rule::exists('business', 'id'),
            ],
            'table_id' => [
                'nullable',
                Rule::exists('tables', 'id'),
            ],
            'fnb_type' => [
                'required',
                'in:'.implode(",",[OrderEnum::FNB_NONE,OrderEnum::FNB_TAKEAWAY,OrderEnum::FNB_DINE_IN])
            ],
            'repeat_order_status' => [
                ($this->type == OrderEnum::TYPE_DUE_DATE) ? "required" : "nullable",
                'in:'.implode(",",[OrderEnum::REPEAT_ORDER_STATUS_TRUE,OrderEnum::REPEAT_ORDER_STATUS_FALSE])
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.exists' => 'Customer tidak ditemukan',
            'provider_id.required' => 'Provider harus diisi',
            'provider_id.exists' => 'Provider tidak ditemukan',
            'type.required' => 'Tipe transaksi tharis diisi',
            'type.in' => 'Tipe transaksi tidak valid',
            'repeater.required' => 'Produk belum dipilih',
            'repeater.array' => 'Produk tidak valid',
            'status.required' => 'Status order harus diisi',
            'status.in' => 'Status order tidak valid',
            'business_id.required' => 'Bisnis harus diisi',
            'business_id.exists' => 'Bisnis tidak ditemukan',
            'table_id.exists' => 'Meja tidak ditemukan',
            'fnb_type.required' => 'Tipe FNB tharis diisi',
            'fnb_type.in' => 'Tipe FNB tidak valid',
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
