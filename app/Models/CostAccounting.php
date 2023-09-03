<?php

namespace App\Models;

use App\Enums\CostAccountingEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostAccounting extends Model
{
    use HasFactory, Loggable,SoftDeletes;
    protected $table = "cost_accountings";
    protected $fillable = [
        'name',
        'description',
        'date',
        'type',
        'nominal',
        'user_id',
        'business_id',
        'author_id'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function getNominalAttribute($value)
    {
        return floatval($value);
    }

    public function type()
    {
        $return = null;

        if($this->type == CostAccountingEnum::TYPE_PEMASUKAN){
            $return = "Pemasukan";
        }
        else if($this->type == CostAccountingEnum::TYPE_PENGELUARAN){
            $return = "Pengeluaran";
        }

        return $return;
    }
}
