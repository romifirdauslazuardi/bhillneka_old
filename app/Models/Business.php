<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use HasFactory, Loggable,SoftDeletes;
    protected $table = "business";
    protected $fillable = [
        'name',
        'slug',
        'location',
        'description',
        'user_id',
        'category_id',
        'village_code',
        'author_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(BusinessCategory::class, 'category_id', 'id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_code', 'code');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function tables()
    {
        return $this->hasMany(Table::class, 'business_id');
    }

    public function user_pay_later()
    {
        return $this->hasOne(UserPayLater::class, 'business_id', 'id');
    }
}
