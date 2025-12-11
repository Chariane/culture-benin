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
    Schema::table('contenus', function (Blueprint $table) {
        $table->date('date_validation')->nullable()->change();
    });
}

public function down()
{
    Schema::table('contenus', function (Blueprint $table) {
        $table->date('date_validation')->nullable(false)->change();
    });
}

};
