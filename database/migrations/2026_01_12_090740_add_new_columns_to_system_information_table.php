<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('system_information', function (Blueprint $table) {
            $table->string('personal_logo')->nullable()->after('mobile_version_logo');
            $table->string('english_banner')->nullable()->after('personal_logo'); // English Language Banner
            $table->string('bangla_banner')->nullable()->after('english_banner'); // Bangla Language Banner
            $table->string('english_header_logo')->nullable()->after('bangla_banner'); // English Site Header Logo
            $table->string('bangla_footer_logo')->nullable()->after('english_header_logo'); // Bangla Site Footer Logo
            $table->string('english_footer_logo')->nullable()->after('bangla_footer_logo'); // English Site Footer Logo
            $table->string('watermark')->nullable()->after('english_footer_logo'); // Watermark
            $table->string('madam_image')->nullable()->after('watermark'); // Khaleda Zia Madam Image
            
            // Note: 'logo' column will be treated as 'Bangla Site Header Logo'
        });
    }

    public function down(): void
    {
        Schema::table('system_information', function (Blueprint $table) {
            $table->dropColumn([
                'personal_logo',
                'english_banner',
                'bangla_banner',
                'english_header_logo',
                'bangla_footer_logo',
                'english_footer_logo',
                'watermark',
                'madam_image'
            ]);
        });
    }
};