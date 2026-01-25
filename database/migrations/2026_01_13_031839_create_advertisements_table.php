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
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('position')->nullable(); // e.g. Header, Sidebar, Footer
            $table->string('image')->nullable();
            $table->string('link')->nullable();     // Redirect URL
            $table->longText('script')->nullable(); // For Google Adsense or JS code
            $table->tinyInteger('type')->default(1)->comment('1=Image, 2=Script');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
