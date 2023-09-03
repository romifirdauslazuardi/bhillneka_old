<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;
use App\Traits\TrixRender;

class Page extends Model
{
    use HasFactory, Loggable,SoftDeletes,HasTrixRichText,TrixRender;
    protected $table = "pages";
    protected $fillable = [
        'name',
        'slug',
        'page-trixFields',
        'author_id',
        'business_id'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }
}
