<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory, Loggable,SoftDeletes;
    protected $table = "news";
    protected $fillable = [
        'title',
        'note',
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

    public function news_recipient()
    {
        return $this->hasMany(NewsRecipient::class, 'news_id');
    }
}
