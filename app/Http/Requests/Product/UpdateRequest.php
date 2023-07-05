<?php

namespace App\Http\Requests\Product;

use App\Enums\BusinessCategoryEnum;
use App\Enums\ProductEnum;
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

        if(in_array(Auth::user()->business->category->name,[BusinessCategoryEnum::JASA,BusinessCategoryEnum::BARANG,BusinessCategoryEnum::FNB])){
            $merge["mikrotik"] = ProductEnum::MIKROTIK_NONE;
        }
        if(in_array(Auth::user()->business->category->name,[BusinessCategoryEnum::JASA])){
            $merge["is_using_stock"] = ProductEnum::IS_USING_STOCK_FALSE;
        }

        $this->merge($merge);
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
            ],
            'price' => [
                'required',
                'numeric',
                'min:1'
            ],
            'weight' => [
                'nullable',
                'min:1'
            ],
            'user_id' => [
                'required',
                Rule::exists('users', 'id'),
            ],
            'is_using_stock' => [
                'required',
                'in:'.implode(",",[ProductEnum::IS_USING_STOCK_TRUE,ProductEnum::IS_USING_STOCK_FALSE])
            ],
            'status' => [
                'required',
                'in:'.implode(",",[ProductEnum::STATUS_TRUE,ProductEnum::STATUS_FALSE])
            ],
            'business_id' => [
                'required',
                Rule::exists('business', 'id'),
            ],
            'code' => [
                'required',
                Rule::unique('products', 'code')->ignore(request()->route()->parameter('id'))->where('business_id', $this->business_id)
            ],
            'mikrotik' => [
                'required',
                'in:'.implode(",",[ProductEnum::MIKROTIK_NONE,ProductEnum::MIKROTIK_HOTSPOT,ProductEnum::MIKROTIK_PPPOE])
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama produk harus diisi',
            'price.required' => 'Harga produk harus diisi',
            'price.numeric' => 'Harga produk harus angka',
            'price.min' => 'Harga produk minimal 1',
            'weight.min' => 'Berat minimal 1 gram',
            'user_id.required' => 'User harus diisi',
            'user_id.exists' => 'User tidak ditemukan',
            'status.required' => 'Status produk harus diisi',
            'status.in' => 'Status produk tidak valid',
            'is_using_stock.required' => 'Apakah produk menggunakan stok harus diisi',
            'is_using_stock.in' => 'Apakah produk menggunakan stok tidak valid',
            'code.required' => 'Kode produk harus diisi',
            'code.unique' => 'Kode produk sudah dipakai',
            'business_id.required' => 'Bisnis harus diisi',
            'business_id.exists' => 'Bisnis tidak ditemukan',
            'mikrotik.required' => 'Jenis mikrotik harus diisi',
            'mikrotik.in' => 'Jenis mikrotik tidak valid',
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
