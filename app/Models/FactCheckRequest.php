<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactCheckRequest extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    // স্ট্যাটাস অনুযায়ী ব্যাজ কালার পাওয়ার জন্য
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'checked' => 'bg-success',
            'rejected' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function factCheckResult()
    {
        return $this->hasOne(FactCheck::class, 'user_submitted_news_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}