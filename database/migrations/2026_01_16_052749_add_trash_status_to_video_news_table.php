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
    Schema::table('video_news', function (Blueprint $table) {
        // ডিফল্ট 0 মানে ট্রাশে নেই, 1 মানে ট্রাশে আছে
        $table->tinyInteger('trash_status')->default(0)->after('status');
    });
}

public function down()
{
    Schema::table('video_news', function (Blueprint $table) {
        $table->dropColumn('trash_status');
    });
}
};
