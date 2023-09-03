<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDoku extends Model
{
    use HasFactory, Loggable,SoftDeletes;
    protected $table = "order_dokus";
    protected $fillable = [
        'order_id',
        'target',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
