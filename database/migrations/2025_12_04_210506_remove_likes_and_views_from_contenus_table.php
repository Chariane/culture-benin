<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contenus', function (Blueprint $table) {
            // Supprimez les colonnes que vous avez ajoutées
            $table->dropColumn(['likes', 'views']); // Remplacez par vos noms de colonnes
        });
    }

    public function down()
    {
        Schema::table('contenus', function (Blueprint $table) {
            // Recréer les colonnes si besoin de rollback
            $table->integer('likes')->default(0);
            $table->integer('views')->default(0);
        });
    }
};