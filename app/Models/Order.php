<?php

namespace App\Models;

use App\Enums\OrderEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, Loggable,SoftDeletes;
    protected $table = "orders";
    protected $fillable = [
        'code',
        'user_id',
        'customer_id',
        'discount',
        'owner_fee',
        'agen_fee',
        'doku_fee',
        'provider_id',
        'note',
        'doku_service_id',
        'doku_acquirer_id',
        'doku_channel_id',
        'doku_token_id',
        'payment_due_date',
        'expired_date',
        'paid_date',
        'payment_url',
        'proof_order',
        'payment_note',
        'status',
        'owner_bank_settlement_id',
        'agen_bank_settlement_id',
        'author_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function doku()
    {
        return $this->hasMany(OrderDoku::class, 'order_id')->orderBy("created_at","DESC");
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function status()
    {
        $return = null;

        if($this->status == OrderEnum::STATUS_PENDING){
            $return = (object) [
                'class' => 'info',
                'msg' => 'PENDING',
            ];
        }
        else if($this->status == OrderEnum::STATUS_WAITING_PAYMENT){
            $return = (object) [
                'class' => 'secondary',
                'msg' => 'WAITING PAYMENT',
            ];
        }
        else if($this->status == OrderEnum::STATUS_SUCCESS){
            $return = (object) [
                'class' => 'success',
                'msg' => 'SUCCESS',
            ];
        }
        else if($this->status == OrderEnum::STATUS_FAILED){
            $return = (object) [
                'class' => 'danger',
                'msg' => 'FAILED',
            ];
        }
        else if($this->status == OrderEnum::STATUS_EXPIRED){
            $return = (object) [
                'class' => 'danger',
                'msg' => 'EXPIRED',
            ];
        }
        else if($this->status == OrderEnum::STATUS_REFUNDED){
            $return = (object) [
                'class' => 'warning',
                'msg' => 'REFUNDED',
            ];
        }
        else if($this->status == OrderEnum::STATUS_TIMEOUT){
            $return = (object) [
                'class' => 'danger',
                'msg' => 'TIMEOUT',
            ];
        }
        else if($this->status == OrderEnum::STATUS_REDIRECT){
            $return = (object) [
                'class' => 'info',
                'msg' => 'REDIRECT',
            ];
        }


        return $return;
    }

    public function getDiscountAttribute($value)
    {
        return (int)$value;
    }

    public function getOwnerFeeAttribute($value)
    {
        return (int)($value);
    }

    public function getAgenFeeAttribute($value)
    {
        return (int)$value;
    }

    public function getDokuFeeAttribute($value)
    {
        return (int)$value;
    }

    public function totalBruto(){
        $total = 0;

        foreach($this->items()->get() as $index => $row){
            $total += $row->qty * $row->product_price;
        }

        return (int)$total;
    }

    public function totalNeto(){
        $total = 0;
        foreach($this->items()->get() as $index => $row){
            $total += ($row->qty * $row->product_price) - $row->discount;
        }

        $total = $total - $this->discount;

        return (int)$total;
    }

    public function incomeAgen(){
        $total = ceil(($this->agen_fee / 100) * $this->totalNeto());
        return (int)$total;
    }

    public function incomeOwnerBruto(){
        $total = floor(($this->owner_fee / 100) * $this->totalNeto());
        return (int)$total;
    }

    public function incomeOwnerNeto(){
        $total = $this->incomeOwnerBruto() - $this->doku_fee;
        return (int)$total;
    }
}
