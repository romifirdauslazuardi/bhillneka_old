<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Bank extends Model
{
    use HasFactory, SoftDeletes, Loggable;
    protected $table = "banks";
    protected $fillable = [
        'name',
    ];
}
