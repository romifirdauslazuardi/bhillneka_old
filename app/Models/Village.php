<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Village extends Model
{
    use HasFactory, Loggable;
    protected $table = "indonesia_villages";
    protected $fillable = [
        'code',
        'district_code',
        'name',
        'meta'
    ];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }
}
