<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactCheck extends Model
{
    use HasFactory;

    protected $guarded = [];

    // পোস্টের সাথে রিলেশন
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    // ইউজার রিকোয়েস্টের সাথে কানেকশন (User Submitted)
    public function factCheckRequest()
    {
        // আমরা কাস্টম ফরেন কি 'user_submitted_news_id' ব্যবহার করছি
        return $this->belongsTo(FactCheckRequest::class, 'user_submitted_news_id');
    }
}