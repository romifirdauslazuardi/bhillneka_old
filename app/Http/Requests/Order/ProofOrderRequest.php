<?php

namespace App\Http\Requests\Order;

use App\Enums\OrderEnum;
use App\Enums\RoleEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\CustomValidationException;
use Illuminate\Validation\Rule;
use Auth;

class ProofOrderRequest extends FormRequest
{

    public function prepareForValidation()
    {
        $merge = [];
        if(request()->routeIs("landing-page.manual-payments.proofOrder")){
            $merge["status"] = OrderEnum::STATUS_PENDING;
        }
        else{
            if(Auth::user()->hasRole([RoleEnum::OWNER])){
                $merge["status"] = OrderEnum::STATUS_SUCCESS;
            }
            else if(Auth::user()->hasRole([RoleEnum::AGEN,RoleEnum::ADMIN_AGEN])){
                $merge["status"] = OrderEnum::STATUS_PENDING;
            }
        }
        $this->merge($merge);
    }

    public function rules(): array
    {
        return [
            'proof_order' => [
                'required',
                'image',
                'max:2048',
                'mimes:jpeg,png,jpg,svg',
            ],
            'payment_note' => [
                'required'
            ],
            'status' => [
                'required',
                'in:'.implode(",",[OrderEnum::STATUS_EXPIRED,OrderEnum::STATUS_FAILED,OrderEnum::STATUS_PENDING,OrderEnum::STATUS_REDIRECT,OrderEnum::STATUS_REFUNDED,OrderEnum::STATUS_SUCCESS,OrderEnum::STATUS_TIMEOUT,OrderEnum::STATUS_WAITING_PAYMENT])
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'proof_order.required' => 'Bukti pembayaran tidak boleh kosong',
            'proof_order.image' => 'Bukti pembayaran harus berupa gambar',
            'proof_order.mimes' => 'Bukti pembayaran harus berupa jpeg,png,jpg,svg',
            'proof_order.max' => 'Bukti pembayaran tidak boleh lebih dari 2MB',
            'payment_note.required' => 'Catatan pembayaran harus diisi',
            'status.required' => 'Status order harus diisi',
            'status.in' => 'Status order tidak valid',
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
