<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class OurService extends Model
{
    use HasFactory, Loggable,SoftDeletes;
    protected $table = "our_services";
    protected $fillable = [
        'name',
        'description',
        'icon',
        'author_id'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }
}
