<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrative extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'designation_id' => 'array',
        'category_id' => 'array',
    ];

   // 2. Helper to get Designation Models
    public function getDesignationsAttribute()
    {
        if (empty($this->designation_id)) return collect([]);
        return Designation::whereIn('id', $this->designation_id)->get();
    }

    // 3. Helper to get Category Models
    public function getCategoriesAttribute()
    {
        if (empty($this->category_id)) return collect([]);
        return AdministrativeCategory::whereIn('id', $this->category_id)->get();
    }

    public function socialLinks()
    {
        return $this->hasMany(AdministrativeSocialLink::class, 'administrative_id');
    }
}