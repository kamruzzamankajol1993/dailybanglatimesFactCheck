<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('administrative_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('eng_name')->nullable();
            $table->string('slug')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            
            // Optional: Foreign key constraint if you want strict hierarchy
             $table->foreign('parent_id')->references('id')->on('administrative_categories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administrative_categories');
    }
};