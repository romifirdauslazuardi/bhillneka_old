<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;
use App\Traits\TrixRender;

class WhyUs extends Model
{
    use HasFactory, Loggable,SoftDeletes,HasTrixRichText,TrixRender;
    protected $table = "why_us";
    protected $fillable = [
        'title',
        'sub_title',
        'whyus-trixFields',
        'author_id',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }
}
