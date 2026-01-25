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
        $table->integer('order_id')->default(0)->after('status');
    });
}

public function down()
{
    Schema::table('administratives', function (Blueprint $table) {
        $table->dropColumn('order_id');
    });
}
};
