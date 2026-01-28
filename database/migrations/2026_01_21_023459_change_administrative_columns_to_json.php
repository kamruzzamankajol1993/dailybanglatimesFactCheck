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
    Schema::table('administratives', function (Blueprint $table) {
        // We use 'text' or 'json' to store the array of IDs
        // Note: We drop the foreign key constraint first if it exists
        
        // $table->dropForeign(['designation_id']); // Uncomment if you had foreign keys
         $table->dropForeign(['category_id']);    // Uncomment if you had foreign keys

        $table->text('designation_id')->change();
        $table->text('category_id')->change();
    });
}

public function down()
{
    Schema::table('administratives', function (Blueprint $table) {
        // Reverting this is risky if you have multiple IDs saved, 
        // but this is how you'd theoretically flip it back.
        $table->integer('designation_id')->change();
        $table->integer('category_id')->change();
    });
}
};
