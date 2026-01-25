<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AdministrativeCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'eng_name',
        'slug',
        'image',
        'status',
    ];

    protected $guarded = ['id'];

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
        return $this->belongsTo(AdministrativeCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(AdministrativeCategory::class, 'parent_id');
    }
}