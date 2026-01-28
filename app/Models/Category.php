<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'eng_name',
        'slug',
        'image',
        'status',
        'order_id',
        'view_on_fact_check_site',
    ];

    protected $guarded = ['id'];

    // Automatically create a slug from the name
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                 $category->slug = Str::slug($category->name);
            }
        });
    }

     public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_category', 'category_id', 'post_id');
    }
    public function factCheckRequests()
    {
        return $this->hasMany(FactCheckRequest::class, 'category_id');
    }
}
