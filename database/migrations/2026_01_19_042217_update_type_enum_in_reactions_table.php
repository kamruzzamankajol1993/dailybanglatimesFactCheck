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
        // We use a raw statement because Doctrine DBAL (used by Laravel for modifying columns) 
        // has trouble with ENUMs sometimes.
        DB::statement("ALTER TABLE reactions MODIFY COLUMN type ENUM('like', 'love', 'haha', 'sad', 'angry') NOT NULL");
    }

    public function down()
    {
        // Revert back if needed
        DB::statement("ALTER TABLE reactions MODIFY COLUMN type ENUM('like', 'love', 'sad', 'angry') NOT NULL");
    }
};
