<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoNews extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'video_url',
        'description',
        'thumbnail',
        'language',
        'status',
        'draft_status',
        'trash_status',
        'view_count'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}