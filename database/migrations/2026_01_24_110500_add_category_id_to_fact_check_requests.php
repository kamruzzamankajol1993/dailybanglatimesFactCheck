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
    Schema::table('fact_check_requests', function (Blueprint $table) {
        $table->foreignId('category_id')
              ->nullable()
              ->after('description') // বা পছন্দমতো জায়গায়
              ->constrained('categories')
              ->onDelete('set null'); // ক্যাটাগরি ডিলিট হলে নাল হবে
    });
}

public function down()
{
    Schema::table('fact_check_requests', function (Blueprint $table) {
        $table->dropForeign(['category_id']);
        $table->dropColumn('category_id');
    });
}
};
