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
    // 1. Remplir les valeurs NULL avec la date du jour
    DB::table('contenus')->whereNull('date_creation')->update([
        'date_creation' => DB::raw('CURRENT_DATE')
    ]);

    DB::table('contenus')->whereNull('date_validation')->update([
        'date_validation' => DB::raw('CURRENT_DATE')
    ]);

    // 2. Modifier les colonnes (ajout du default + NOT NULL)
    Schema::table('contenus', function (Blueprint $table) {
        $table->date('date_creation')->default(DB::raw('CURRENT_DATE'))->change();
        $table->date('date_validation')->default(DB::raw('CURRENT_DATE'))->change();
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
