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
        $table->unsignedBigInteger('id_moderateur')->nullable()->change();
    });
}

public function down()
{
    Schema::table('contenus', function (Blueprint $table) {
        $table->unsignedBigInteger('id_moderateur')->nullable(false)->change();
    });
}

};
