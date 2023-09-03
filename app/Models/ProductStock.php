<?php

namespace App\Models;

use App\Enums\ProductStockEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductStock extends Model
{
    use HasFactory, Loggable,SoftDeletes;
    protected $table = "product_stocks";
    protected $fillable = [
        'date',
        'type',
        'product_id',
        'qty',
        'available',
        'note',
        'author_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function type()
    {
        $return = null;

        if($this->type == ProductStockEnum::TYPE_MASUK){
            $return = "Stok Masuk";
        }
        else if($this->type == ProductStockEnum::TYPE_KELUAR){
            $return = "Stok Keluar";
        }

        return $return;
    }
}
