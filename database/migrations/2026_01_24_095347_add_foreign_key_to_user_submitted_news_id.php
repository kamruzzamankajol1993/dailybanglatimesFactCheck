<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('fact_checks', function (Blueprint $table) {
        // বিদ্যমান কলামটিকে ফরেন কি (Foreign Key) বানানো হচ্ছে
        $table->foreign('user_submitted_news_id')
              ->references('id')
              ->on('fact_check_requests')
              ->onDelete('cascade'); // রিকোয়েস্ট ডিলিট হলে রেজাল্টও ডিলিট হবে
    });
}

public function down()
{
    Schema::table('fact_checks', function (Blueprint $table) {
        $table->dropForeign(['user_submitted_news_id']);
    });
}
};
