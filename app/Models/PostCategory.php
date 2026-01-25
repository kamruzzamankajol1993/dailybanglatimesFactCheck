<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    use HasFactory;

    // টেবিলের নাম (যেহেতু কনভেনশন অনুযায়ী নাম category_post হওয়ার কথা, কিন্তু আমরা post_category দিয়েছি)
    protected $table = 'post_category';

    // পিভট টেবিলে সাধারণত টাইমস্ট্যাম্প থাকে না, যদি আপনার মাইগ্রেশনে timestamps() না থাকে তবে এটি false করে দিন
    public $timestamps = false; 

    protected $fillable = [
        'post_id',
        'category_id'
    ];
}