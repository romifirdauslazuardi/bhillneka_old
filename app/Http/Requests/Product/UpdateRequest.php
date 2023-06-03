<?php

namespace App\Http\Requests\Product;

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
        if(Auth::user()->hasRole([RoleEnum::AGEN])){
            $merge["user_id"] = Auth::user()->id;
        }
        if(Auth::user()->hasRole([RoleEnum::ADMIN_AGEN])){
            $merge["user_id"] = Auth::user()->user_id;
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
            'category_id' => [
                'required',
                Rule::exists('product_categories', 'id'),
            ],
            'unit_id' => [
                'required',
                Rule::exists('units', 'id'),
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
            'code' => [
                'required',
                Rule::unique('products', 'code')->ignore(request()->route()->parameter('id'))->where('user_id', request()->get("user_id"))
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
            'category_id.required' => 'Kategori produk harus diisi',
            'category_id.exists' => 'Kategori produk tidak ditemukan',
            'unit_id.required' => 'Unit harus diisi',
            'unit_id.exists' => 'Unit tidak ditemukan',
            'user_id.required' => 'User harus diisi',
            'user_id.exists' => 'User tidak ditemukan',
            'status.required' => 'Status produk harus diisi',
            'status.in' => 'Status produk tidak valid',
            'is_using_stock.required' => 'Apakah produk menggunakan stok harus diisi',
            'is_using_stock.in' => 'Apakah produk menggunakan stok tidak valid',
            'code.required' => 'Kode produk harus diisi',
            'code.unique' => 'Kode produk sudah dipakai',
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
