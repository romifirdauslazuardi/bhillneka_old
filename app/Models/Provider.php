<?php

namespace App\Models;

use App\Enums\ProviderEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use HasFactory, Loggable,SoftDeletes;
    protected $table = "providers";
    protected $fillable = [
        'name',
        'client_id',
        'secret_key',
        'type',
        'note',
        'status',
        'author_id',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function type()
    {
        $return = null;

        if($this->status == ProviderEnum::TYPE_MANUAL_TRANSFER){
            $return = "Manual Transfer";
        }
        else if($this->status == ProviderEnum::TYPE_DOKU){
            $return = "Doku";
        }

        return $return;
    }

    public function status()
    {
        $return = null;

        if($this->status == ProviderEnum::STATUS_FALSE){
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
