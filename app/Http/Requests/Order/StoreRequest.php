<?php

namespace App\Http\Requests\Order;

use App\Enums\BusinessCategoryEnum;
use App\Enums\RoleEnum;
use App\Enums\OrderEnum;
use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class StoreRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $merge = [];
        if(!request()->routeIs("landing-page.buy-products.store")){
            $merge["user_id"] = Auth::user()->business->user_id ?? null;
            $merge["business_id"] = Auth::user()->business_id;

            if(!in_array(Auth::user()->business->category->name,[BusinessCategoryEnum::FNB])){
                $merge["fnb_type"] = OrderEnum::FNB_NONE;
            }
        }
        else{
            $merge["type"] = OrderEnum::TYPE_ON_TIME_PAY;
            $merge["fnb_type"] = OrderEnum::FNB_NONE;
        }
        $this->merge($merge);
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                Rule::exists('users', 'id'),
            ],
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
            'customer_name' => [
                (request()->routeIs("landing-page.buy-products.store")) ? "required" : "nullable"
            ],
            'customer_phone' => [
                (request()->routeIs("landing-page.buy-products.store")) ? "required" : "nullable"
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User harus diisi',
            'user_id.exists' => 'User tidak ditemukan',
            'customer_id.exists' => 'Customer tidak ditemukan',
            'provider_id.required' => 'Provider harus diisi',
            'provider_id.exists' => 'Provider tidak ditemukan',
            'type.required' => 'Tipe transaksi harus diisi',
            'type.in' => 'Tipe transaksi tidak valid',
            'repeater.required' => 'Produk belum dipilih',
            'repeater.array' => 'Produk tidak valid',
            'business_id.required' => 'Bisnis harus diisi',
            'business_id.exists' => 'Bisnis tidak ditemukan',
            'table_id.exists' => 'Meja tidak ditemukan',
            'fnb_type.required' => 'Tipe FNB harus diisi',
            'fnb_type.in' => 'Tipe FNB tidak valid',
            'customer_name.required' => 'Nama customer harus diisi',
            'customer_phone.required' => 'Telp customer harus diisi',
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
        else{
            throw new CustomValidationException($validator);
        }
    }
}
