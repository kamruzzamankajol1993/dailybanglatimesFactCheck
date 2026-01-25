<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministrativeSocialLink extends Model
{
    use HasFactory;

    protected $fillable = ['administrative_id', 'name', 'icon', 'url'];

    public function administrative()
    {
        return $this->belongsTo(Administrative::class, 'administrative_id');
    }
}