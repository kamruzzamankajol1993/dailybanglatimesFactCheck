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
    Schema::table('categories', function (Blueprint $table) {
        // ডিফল্ট ০ (মানে দেখাবে না), ১ হলে দেখাবে
        $table->boolean('view_on_fact_check_site')->default(0)->after('status');
    });
}

public function down()
{
    Schema::table('categories', function (Blueprint $table) {
        $table->dropColumn('view_on_fact_check_site');
    });
}
};
