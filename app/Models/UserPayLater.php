<?php

namespace App\Models;

use App\Enums\UserPayLaterEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPayLater extends Model
{
    use HasFactory, Loggable,SoftDeletes;
    protected $table = "user_pay_laters";
    protected $fillable = [
        'user_id',
        'business_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    public function status()
    {
        $return = null;

        if($this->status == UserPayLaterEnum::STATUS_FALSE){
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
}
