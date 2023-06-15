<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\ProductEnum;

class Product extends Model
{
    use HasFactory, Loggable,SoftDeletes;
    protected $table = "products";
    protected $fillable = [
        'code',
        'name',
        'slug',
        'price',
        'description',
        'user_id',
        'category_id',
        'unit_id',
        'status',
        'is_using_stock',
        'author_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class, 'product_id')->orderBy("created_at","DESC");
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function getPriceAttribute($value)
    {
        return (int)$value;
    }

    public function status()
    {
        $return = null;

        if($this->status == ProductEnum::STATUS_FALSE){
            $return = (object) [
                'class' => 'warning',
                'msg' => 'Tidak Aktif',
            ];
        }
        else{
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

        if($this->is_using_stock == ProductEnum::IS_USING_STOCK_TRUE){
            $return = (object) [
                'class' => 'success',
                'msg' => 'Ya',
            ];
        }
        else{
            $return = (object) [
                'class' => 'warning',
                'msg' => 'Tidak',
            ];
        }

        return $return;
    }
}
