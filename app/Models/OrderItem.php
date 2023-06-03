<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, Loggable,SoftDeletes;
    protected $table = "order_items";
    protected $fillable = [
        'order_id',
        'product_code',
        'product_id',
        'product_name',
        'product_price',
        'qty',
        'discount',
        'total',
        'author_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function getProductPriceAttribute($value)
    {
        return floatval($value);
    }

    public function getQtyAttribute($value)
    {
        return floatval($value);
    }

    public function getDiscountAttribute($value)
    {
        return floatval($value);
    }

    public function getTotalAttribute($value)
    {
        return floatval($value);
    }

    public function totalBruto(){
        $total = $this->qty * $this->product_price;

        return $total;
    }

    public function totalNeto(){
        $total = ($this->qty * $this->product_price) - $this->discount;

        return $total;
    }
}
