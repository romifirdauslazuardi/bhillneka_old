<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class District extends Model
{
    use HasFactory, Loggable;
    protected $table = "indonesia_districts";
    protected $fillable = [
        'code',
        'city_code',
        'name',
        'meta'
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_code', 'code');
    }
}
