<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('abonnements', function (Blueprint $table) {
        // Clé primaire composite
        $table->primary(['id_auteur', 'id_abonne']);
        
        // Clés étrangères - Spécifiez explicitement la colonne de référence
        $table->unsignedBigInteger('id_auteur');
        $table->unsignedBigInteger('id_abonne');
        
        // Contraintes de clé étrangère
        $table->foreign('id_auteur')
              ->references('id_utilisateur')
              ->on('utilisateurs')
              ->onDelete('cascade');
              
        $table->foreign('id_abonne')
              ->references('id_utilisateur')
              ->on('utilisateurs')
              ->onDelete('cascade');
        
        // Colonnes spécifiques
        $table->timestamp('date_abonnement')->useCurrent();
        $table->boolean('notifications_actives')->default(true);
        
        // Timestamps optionnels
        $table->timestamps();
        
        // Index pour les performances
        $table->index(['id_abonne', 'date_abonnement']);
        $table->index(['id_auteur', 'date_abonnement']);
    });
}

    public function down()
    {
        Schema::dropIfExists('abonnements');
    }
};