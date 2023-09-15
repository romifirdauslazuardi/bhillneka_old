<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCategory extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "product_categories";

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function business(){
        return $this->belongsTo(Business::class, 'business_id');
    }
    public function image(){
        if ($this->image) {
            return Storage::url($this->image);
        }
        return asset('assets/placeholder-image.webp');
    }
}
