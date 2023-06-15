<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Testimonial extends Model
{
    use HasFactory, SoftDeletes, Loggable;
    protected $table = "testimonials";
    protected $fillable = [
        'name',
        'position',
        'message',
        'star',
        'avatar',
        'author_id'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }
}
