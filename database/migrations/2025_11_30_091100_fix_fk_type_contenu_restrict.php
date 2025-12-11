<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contenus', function (Blueprint $table) {
            // SQLite ne supporte pas toujours dropForeign avec le nom simple,
            // mais Laravel le gère si on utilise la méthode Schema
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('contenus_id_type_contenu_foreign');
            } else {
                // Pour SQLite, c'est plus compliqué car il faut souvent recréer la table
                // Mais essayons la méthode standard Laravel qui tente de gérer ça
                $table->dropForeign(['id_type_contenu']);
            }
            
            $table->foreign('id_type_contenu')
                  ->references('id_type_contenu')
                  ->on('type_contenus')
                  ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::table('contenus', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('contenus_id_type_contenu_foreign');
            } else {
                $table->dropForeign(['id_type_contenu']);
            }

            $table->foreign('id_type_contenu')
                  ->references('id_type_contenu')
                  ->on('type_contenus');
        });
    }
};
