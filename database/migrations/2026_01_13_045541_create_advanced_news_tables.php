<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Advanced Post Table
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // যে আপলোড করেছে
            $table->unsignedBigInteger('approved_by')->nullable(); // যে এপ্রুভ করেছে
            
            $table->string('title');
            $table->text('subtitle')->nullable(); // সাব-টাইটেল
            $table->boolean('breaking_news')->default(0)->comment('0=No, 1=Yes'); // ব্রেকিং নিউজ
            $table->string('home_page_position')->nullable(); // হোম পেজ পজিশন (slider/madam image)
            
            $table->string('slug')->unique();
            $table->longText('content');
            $table->string('source')->nullable(); // সোর্স (Source)
            $table->timestamp('bangladesh_time')->nullable(); // বাংলাদেশের সময়
            
            $table->string('image')->nullable();
            $table->string('image_caption')->nullable(); // ইমেজ ক্যাপশন

         
            $table->string('facebook_image')->nullable(); // New
            $table->string('old_id')->nullable();
            $table->string('old_category_name')->nullable();

            // Language & Categorization
            $table->enum('language', ['bn', 'en'])->default('bn'); // বাংলা বা ইংলিশ
            $table->unsignedBigInteger('category_id')->nullable();
            
            // Status, Draft, Trash & Views
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('draft_status')->default(0)->comment('0=Published/Pending, 1=Draft'); // ড্রাফট স্ট্যাটাস
            $table->boolean('trash_status')->default(0)->comment('0=Active, 1=Trashed'); // ট্রাশ স্ট্যাটাস (ডিলিট লজিক)
            
            $table->bigInteger('view_count')->default(0); // ভিউ কাউন্ট
            
            $table->timestamps();
            // $table->softDeletes(); // Soft Delete বাদ দেওয়া হয়েছে, trash_status ব্যবহার হবে
        });

        // 2. Post Activity Logs (কে কখন কি করলো)
        Schema::create('post_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id'); // যে অ্যাকশন নিয়েছে
            $table->string('action'); // created, updated, approved, rejected, trashed, restored
            $table->text('note')->nullable(); // কি এডিট করলো তার নোট (অপশনাল)
            $table->timestamps();
            
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });

        // 3. Comments with Reply & Approval
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id')->nullable(); // লগইন ইউজার হলে
            $table->string('name')->nullable(); // গেস্ট হলে
            $table->text('body');
            $table->unsignedBigInteger('parent_id')->nullable(); // রিপ্লাই এর জন্য (Self Referencing)
            $table->boolean('status')->default(0); // 0=Pending, 1=Approved
            $table->timestamps();

            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });
        
        // 4. Reactions (Reacts)
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address')->nullable(); // ইউনিক রিয়েক্ট এর জন্য
            $table->enum('type', ['like', 'love', 'sad', 'angry'])->default('like');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reactions');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('post_logs');
        Schema::dropIfExists('posts');
    }
};