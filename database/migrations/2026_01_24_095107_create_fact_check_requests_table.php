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
        Schema::create('fact_check_requests', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(); // ইউজার টাইটেল দিতে পারে
        $table->string('link')->nullable();  // অথবা নিউজের লিংক
        $table->string('image')->nullable(); // অথবা স্ক্রিনশট
        $table->longText('description')->nullable(); // ইউজার কিছু বলতে চাইলে
        $table->string('status')->default('pending'); // pending, checked, rejected
        
        // অ্যাডমিনের চেক করা ডাটা
        $table->string('admin_verdict')->nullable(); // True, False
        $table->longText('admin_comment')->nullable();   // ব্যাখ্যা
        $table->foreignId('checked_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fact_check_requests');
    }
};
