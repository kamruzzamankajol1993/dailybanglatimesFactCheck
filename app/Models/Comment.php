<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'name',
        'body',
        'parent_id',
        'status' // 0=Pending, 1=Approved
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // রিপ্লাই পাওয়ার জন্য (Children)
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->where('status', 1);
    }
    
    // এডমিনের জন্য সব রিপ্লাই (পেন্ডিং সহ)
    public function allReplies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    // প্যারেন্ট কমেন্ট পাওয়ার জন্য
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}