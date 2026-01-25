<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('system_information', function (Blueprint $table) {
            // US Office Address কলাম (existing address এর পরে)
            $table->text('us_office_address')->nullable()->after('address');
            
            // Long Description কলাম (existing description এর পরে)
            $table->longText('long_description')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('system_information', function (Blueprint $table) {
            $table->dropColumn(['us_office_address', 'long_description']);
        });
    }
};