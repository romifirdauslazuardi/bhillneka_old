<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\UserBankEnum;

class UserBank extends Model
{
    use HasFactory, Loggable,SoftDeletes;
    protected $table = "user_banks";
    protected $fillable = [
        'name',
        'number',
        'user_id',
        'bank_id',
        'status',
        'author_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function status()
    {
        $return = null;

        if($this->status == UserBankEnum::STATUS_WAITING_APPROVE){
            $return = (object) [
                'class' => 'warning',
                'msg' => 'Menunggu Diverifikasi',
            ];
        }
        else if($this->status == UserBankEnum::STATUS_APPROVED){
            $return = (object) [
                'class' => 'success',
                'msg' => 'Terverifikasi',
            ];
        }
        else{
            $return = (object) [
                'class' => 'danger',
                'msg' => 'Ditolak',
            ];
        }

        return $return;
    }
}
