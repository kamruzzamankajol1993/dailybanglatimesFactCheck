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
    Schema::table('fact_checks', function (Blueprint $table) {
        // নতুন কলাম: কে চেক করেছে (Google API নাকি Gemini)
        $table->string('check_source')->nullable()->after('confidence_score');
    });
}

public function down()
{
    Schema::table('fact_checks', function (Blueprint $table) {
        $table->dropColumn('check_source');
    });
}
};
