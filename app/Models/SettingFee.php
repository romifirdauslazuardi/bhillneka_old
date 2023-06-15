<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\ProductEnum;

class SettingFee extends Model
{
    use HasFactory, Loggable,SoftDeletes;
    protected $table = "setting_fee";
    protected $fillable = [
        'owner_fee',
        'agen_fee',
    ];

    public function getOwnerFeeAttribute($value)
    {
        return (int)$value;
    }

    public function getAgenFeeAttribute($value)
    {
        return (int)$value;
    }
}
