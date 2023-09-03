<?php

namespace App\Models;

use App\Enums\UserEnum;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Lab404\Impersonate\Models\Impersonate;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable,Loggable,Impersonate,HasRoles,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'user_id',
        'email_verified_at',
        'author_id',
        'provider',
        'business_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function provider()
    {
        $return = null;

        if($this->provider == UserEnum::PROVIDER_MANUAL){
            $return = "Manual";
        }
        else{
            $return = "Google";
        }

        return $return;
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    public function hasManyBusiness()
    {
        return $this->hasMany(Business::class, 'user_id');
    }

    public function status()
    {
        $return = null;

        if(!empty($this->deleted_at)){
            $return = (object) [
                'class' => 'danger',
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
