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
        $table->boolean('premium')->default(false)->change();
        $table->decimal('prix', 8, 2)->nullable(); // prix obligatoire → géré par validation
    });
}

public function down()
{
    Schema::table('contenus', function (Blueprint $table) {
        $table->dropColumn('prix');
    });
}

};
