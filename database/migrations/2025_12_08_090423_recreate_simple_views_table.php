<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. D'abord, supprimer la vue matérialisée qui dépend de la table
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS vue_statistiques_contenu');
        
        // 2. Maintenant, supprimer la table views
        Schema::dropIfExists('views');
        
        // 3. Recréer la table avec une structure simplifiée
        Schema::create('views', function (Blueprint $table) {
            $table->id('id_view');
            $table->unsignedBigInteger('id_contenu');
            $table->unsignedBigInteger('id_utilisateur');
            $table->dateTime('date')->nullable();
            
            $table->timestamps(); // created_at et updated_at

            // Clés étrangères
            $table->foreign('id_contenu')
                ->references('id_contenu')
                ->on('contenus')
                ->onDelete('cascade');

            $table->foreign('id_utilisateur')
                ->references('id_utilisateur')
                ->on('utilisateurs')
                ->onDelete('cascade');

            // Empêcher les doublons - un utilisateur ne peut avoir qu'une entrée par contenu
            $table->unique(['id_contenu', 'id_utilisateur']);
        });
    }

    public function down()
    {
        // En cas de rollback, supprimer la table
        Schema::dropIfExists('views');
    }
};