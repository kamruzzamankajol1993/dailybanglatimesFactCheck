<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'ins_name',
        'logo',
        'branch_id',
        'designation_id',
        'keyword',
        'description',       // Existing Short/SEO Description
        'long_description',  // New Long Description
        'develop_by',
        'icon',
        'mobile_version_logo',
        'address',           // Existing Address
        'us_office_address', // New US Address
        'email',
        'phone',
        'email_one',
        'phone_one',
        'main_url',
        'front_url',
        'english_url',
        'fact_check_url',
        'tax',
        'charge',
        'usdollar',
        // ... previous added columns (personal_logo, banners etc.) ...
        'personal_logo',
        'english_banner',
        'bangla_banner',
        'english_header_logo',
        'bangla_footer_logo',
        'english_footer_logo',
        'watermark',
        'madam_image',
    ];
}