<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use HasFactory, Loggable,SoftDeletes;
    protected $table = "product_categories";
    protected $fillable = [
        'name',
        'business_category_id',
        'author_id',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function business_category()
    {
        return $this->belongsTo(BusinessCategory::class, 'business_category_id', 'id');
    }
}
