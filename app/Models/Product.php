<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\ProductEnum;

class Product extends Model
{
    use HasFactory, Loggable, SoftDeletes;
    protected $table = "products";
    protected $fillable = [
        'code',
        'name',
        'slug',
        'price',
        'image',
        'description',
        'user_id',
        'weight',
        'status',
        'is_using_stock',
        'business_id',
        'mikrotik',
        'profile',
        'server',
        'service',
        'comment',
        'time_limit',
        'author_id',
        'local_address',
        'remote_address',
        'address',
        'mac_address',
        'expired_month',
        'author_id',
        'mikrotik_config_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class, 'product_id')->orderBy("created_at", "DESC");
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    public function mikrotik_config()
    {
        return $this->belongsTo(MikrotikConfig::class, 'mikrotik_config_id', 'id');
    }

    public function getPriceAttribute($value)
    {
        return (int)$value;
    }

    public function status()
    {
        $return = null;

        if ($this->status == ProductEnum::STATUS_FALSE) {
            $return = (object) [
                'class' => 'warning',
                'msg' => 'Tidak Aktif',
            ];
        } else {
            $return = (object) [
                'class' => 'success',
                'msg' => 'Aktif',
            ];
        }

        return $return;
    }

    public function is_using_stock()
    {
        $return = null;

        if ($this->is_using_stock == ProductEnum::IS_USING_STOCK_TRUE) {
            $return = (object) [
                'class' => 'success',
                'msg' => 'Ya',
            ];
        } else {
            $return = (object) [
                'class' => 'warning',
                'msg' => 'Tidak',
            ];
        }

        return $return;
    }

    public function mikrotik()
    {
        $return = null;

        if ($this->mikrotik == ProductEnum::MIKROTIK_PPPOE) {
            $return = "PPPOE";
        } else if ($this->mikrotik == ProductEnum::MIKROTIK_HOTSPOT) {
            $return = "Hotspot";
        }

        return $return;
    }

    public function weight()
    {
        return $this->weight . " gram";
    }
}
