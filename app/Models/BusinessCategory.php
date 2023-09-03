<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class BusinessCategory extends Model
{
    use HasFactory, SoftDeletes, Loggable;
    protected $table = "business_categories";
    protected $fillable = [
        'name',
        'template_id',
        'author_id',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function template()
    {
        return $this->belongsTo(LandingTemplate::class, 'template_id', 'id');
    }
}
