<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\ProductEnum;
use App\Enums\SettingCustomerFeeEnum;

class SettingCustomerFee extends Model
{
    use HasFactory, Loggable,SoftDeletes;
    protected $table = "setting_customer_fee";
    protected $fillable = [
        'mark',
        'limit',
        'type',
        'value',
    ];

    public function getLimitAttribute($value)
    {
        return floatval($value);
    }

    public function getValueAttribute($value)
    {
        return floatval($value);
    }

    public function mark()
    {
        $return = null;

        if($this->mark == SettingCustomerFeeEnum::MARK_KURANG_DARI){
            $return = "Kurang Dari";
        }
        else if($this->mark == SettingCustomerFeeEnum::MARK_LEBIH_DARI){
            $return = "Lebih dari";
        }

        return $return;
    }

    public function type()
    {
        $return = null;

        if($this->type == SettingCustomerFeeEnum::TYPE_PERCENTAGE){
            $return = "Percentage (%)";
        }
        else if($this->type == SettingCustomerFeeEnum::TYPE_FIXED){
            $return = "Fixed";
        }

        return $return;
    }
}
