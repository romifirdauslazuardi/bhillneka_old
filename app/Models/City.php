<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class City extends Model
{
    use HasFactory, Loggable;
    protected $table = "indonesia_cities";
    protected $fillable = [
        'code',
        'province_code',
        'name',
        'meta'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }
}
