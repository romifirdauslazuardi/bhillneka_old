<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\SettingFeeEnum;

class SettingFee extends Model
{
    use HasFactory, Loggable,SoftDeletes;
    protected $table = "setting_fee";
    protected $fillable = [
        'mark',
        'limit',
        'owner_fee',
        'agen_fee',
    ];

    public function getLimitAttribute($value)
    {
        return floatval($value);
    }

    public function getOwnerFeeAttribute($value)
    {
        return floatval($value);
    }

    public function getAgenFeeAttribute($value)
    {
        return floatval($value);
    }

    public function mark()
    {
        $return = null;

        if($this->mark == SettingFeeEnum::MARK_KURANG_DARI){
            $return = "Kurang Dari";
        }
        else if($this->mark == SettingFeeEnum::MARK_LEBIH_DARI){
            $return = "Lebih dari";
        }

        return $return;
    }
}
