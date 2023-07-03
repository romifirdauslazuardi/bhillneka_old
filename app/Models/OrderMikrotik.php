<?php

namespace App\Models;

use App\Enums\OrderMikrotikEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderMikrotik extends Model
{
    use HasFactory, Loggable,SoftDeletes;
    protected $table = "order_item_mikrotiks";
    protected $fillable = [
        'auto_userpassword',
        'mikrotik_id',
        'order_item_id',
        'username',
        'password',
        'profile',
        'server',
        'service',
        'disabled',
        'type',
        'comment',
        'time_limit',
        'author_id',
        'local_address',
        'remote_address',
        'address',
        'mac_address',
    ];

    public function order_item()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function type()
    {
        $return = null;

        if($this->type == OrderMikrotikEnum::TYPE_PPPOE){
            $return = "PPPOE";
        }
        else if($this->type == OrderMikrotikEnum::TYPE_HOTSPOT){
            $return = "Hotspot";
        }

        return $return;
    }

    public function auto_userpassword()
    {
        $return = null;

        if($this->auto_userpassword == OrderMikrotikEnum::AUTO_USERPASSWORD_TRUE){
            $return = "Auto";
        }
        else if($this->auto_userpassword == OrderMikrotikEnum::AUTO_USERPASSWORD_FALSE){
            $return = "Input Manual";
        }

        return $return;
    }

    public function totalSecond(){
        $totalSecond = 0;

        if($this->type == OrderMikrotikEnum::TYPE_HOTSPOT){
            $explodeTimeLimit = str_split($this->time_limit);
            
            foreach($explodeTimeLimit as $i => $value){
                if(isset($explodeTimeLimit[$i+1])){
                    if(strtolower($explodeTimeLimit[$i+1]) == "d"){
                        $totalSecond += (((int)$value) * 24 * 60 * 60);
                    }
                    if(strtolower($explodeTimeLimit[$i+1]) == "h"){
                        $totalSecond += (((int)$value) * 60 * 60);
                    }
                    if(strtolower($explodeTimeLimit[$i+1]) == "m"){
                        $totalSecond += (((int)$value) * 60);
                    }
                    if(strtolower($explodeTimeLimit[$i+1]) == "s"){
                        $totalSecond += (((int)$value));
                    }
                }
            }
        }

        return $totalSecond;
    }
}
