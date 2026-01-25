<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('system_information', function (Blueprint $table) {
        $table->string('english_url')->nullable()->after('front_url');
    });
}

public function down()
{
    Schema::table('system_information', function (Blueprint $table) {
        $table->dropColumn('english_url');
    });
}
};
