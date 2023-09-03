<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingTemplate extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function business_category()
    {
        return $this->hasMany(BusinessCategory::class, 'template_id');
    }
}
