<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    use HasFactory;

    // আমরা সব ফিল্ডে ডাটা ইনসার্ট করার পারমিশন দিচ্ছি (id বাদে)
    protected $fillable = [
        'user_id',
        'approved_by',
        'title',
        'subtitle',            // New
        'breaking_news',       // New
        'home_page_position',  // New
        'slug',
        'content',
        'source',              // New
        'image',
        'facebook_image',      // New
        'old_id',
        'old_category_name',
        'image_caption',       // New
        'language',
        'category_id',
        'status',
        'draft_status',        // New
        'trash_status',        // New
        'view_count',
        'bangladesh_time'    // New
    ];

    /**
     * The "booted" method of the model.
     * এখানে গ্লোবাল স্কোপ যুক্ত করা হয়েছে যাতে ডিফল্টভাবে ট্রাশ করা পোস্টগুলো না আসে।
     */
    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('trash_status', 0);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * পোস্টের লেখক (User)
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * যে এডমিন বা এডিটর পোস্টটি এপ্রুভ করেছেন
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * পোস্টের ক্যাটাগরি সমূহ (Many-to-Many)
     * পিভট টেবিল: post_category
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'post_category', 'post_id', 'category_id');
    }

    /**
     * পোস্টের ট্যাগ সমূহ (Many-to-Many)
     * পিভট টেবিল: post_tag
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }

    /**
     * পোস্টের কমেন্ট সমূহ (ফ্রন্টএন্ডের জন্য)
     * শুধুমাত্র প্যারেন্ট কমেন্ট এবং এপ্রুভ করা কমেন্ট আসবে।
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->where('status', 1);
    }

    /**
     * পোস্টের সকল কমেন্ট (এডমিনের জন্য)
     * পেন্ডিং এবং এপ্রুভড সব কমেন্ট আসবে।
     */
    public function allComments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * পোস্টের অ্যাক্টিভিটি লগ (History)
     * কে কখন এডিট বা এপ্রুভ করেছে তার হিস্টোরি।
     */
    public function logs()
    {
        return $this->hasMany(PostLog::class)->orderBy('created_at', 'desc');
    }

    /**
     * পোস্টের রিয়েকশন (Like, Love, etc.)
     */
    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }
}