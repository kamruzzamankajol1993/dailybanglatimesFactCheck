<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fact_checks', function (Blueprint $table) {
            $table->id();
            // পোস্ট টেবিলের সাথে রিলেশন (Admin News)
            $table->foreignId('post_id')->nullable()->constrained('posts')->onDelete('cascade');
            
            // ইউজার সাবমিটেড নিউজের জন্য (Future use)
            $table->unsignedBigInteger('user_submitted_news_id')->nullable();

            // API রেসপন্স এবং ফলাফল
            $table->string('verdict')->nullable(); // যেমন: True, False, Misleading
            $table->decimal('confidence_score', 5, 2)->nullable(); // যেমন: 0.95
            $table->text('api_response_raw')->nullable(); // সম্পূর্ণ JSON ডাটা
            $table->unsignedBigInteger('checked_by')->nullable(); // কে চেক করেছে
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fact_checks');
    }
};
