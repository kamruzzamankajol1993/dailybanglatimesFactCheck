<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Administrative Table
        Schema::create('administratives', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // Assuming 'designations' table exists based on Designation model
            $table->unsignedBigInteger('designation_id')->nullable(); 
            // References the AdministrativeCategory created previously
            $table->unsignedBigInteger('category_id')->nullable(); 
            $table->string('image')->nullable();
            $table->text('short_description')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            
            // Foreign Keys (Optional - uncomment if you want strict constraints)
            // $table->foreign('designation_id')->references('id')->on('designations')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('administrative_categories')->onDelete('cascade');
        });

        // Social Links Table (One to Many)
        Schema::create('administrative_social_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('administrative_id');
            $table->string('name')->nullable(); // e.g. Facebook, LinkedIn
            $table->string('icon')->nullable(); // e.g. fab fa-facebook
            $table->string('url')->nullable();
            $table->timestamps();

            $table->foreign('administrative_id')->references('id')->on('administratives')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administrative_social_links');
        Schema::dropIfExists('administratives');
    }
};