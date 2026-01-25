<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    use HasFactory;

    // টেবিলের নাম
    protected $table = 'post_tag';

    // টাইমস্ট্যাম্প বন্ধ রাখা হয়েছে (মাইগ্রেশনে না থাকলে)
    public $timestamps = false;

    protected $fillable = [
        'post_id',
        'tag_id'
    ];
}