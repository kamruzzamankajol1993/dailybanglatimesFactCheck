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
        Schema::create('video_news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('video_url'); // YouTube or Facebook Link
            $table->longText('description')->nullable();
            $table->string('thumbnail')->nullable(); // Custom Thumbnail (Optional)
            $table->enum('language', ['bn', 'en'])->default('bn');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('draft_status')->default(0);
            $table->integer('view_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_news');
    }
};
