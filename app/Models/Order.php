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
        'fee',
        'provider_id',
        'note',
        'status',
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
        return floatval($value);
    }

    public function getFeeAttribute($value)
    {
        return floatval($value);
    }

    public function totalBruto(){
        $total = 0;

        foreach($this->items()->get() as $index => $row){
            $total += $row->qty * $row->product_price;
        }

        return $total;
    }

    public function totalNeto(){
        $total = 0;
        foreach($this->items()->get() as $index => $row){
            $total += ($row->qty * $row->product_price) - $row->discount;
        }

        $total = $total - $this->discount;

        return $total;
    }

    public function income(){
        return $this->totalNeto() - $this->fee;
    }
}
