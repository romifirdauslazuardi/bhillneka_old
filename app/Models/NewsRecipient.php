<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsRecipient extends Model
{
    use HasFactory, Loggable,SoftDeletes;
    protected $table = "news_recipients";
    protected $fillable = [
        'news_id',
        'user_id',
        'author_id'
    ];

    public function news()
    {
        return $this->belongsTo(News::class, 'news_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }
}
