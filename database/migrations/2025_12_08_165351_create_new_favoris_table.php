<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('favoris', function (Blueprint $table) {
            // Clé primaire auto-incrémentée
            $table->id('id_favori');
            
            // Clés étrangères
            $table->unsignedBigInteger('id_utilisateur');
            $table->unsignedBigInteger('id_contenu');
            
            // Date d'ajout
            $table->dateTime('date_ajout')->useCurrent();
            
            // Pas de timestamps Laravel (created_at, updated_at) si vous ne voulez pas
            // $table->timestamps();
            
            // Contraintes de clés étrangères
            $table->foreign('id_utilisateur')
                  ->references('id_utilisateur')
                  ->on('utilisateurs')
                  ->onDelete('cascade');
                  
            $table->foreign('id_contenu')
                  ->references('id_contenu')
                  ->on('contenus')
                  ->onDelete('cascade');
            
            // Contrainte d'unicité pour éviter les doublons
            // Un utilisateur ne peut mettre un contenu en favori qu'une seule fois
            $table->unique(['id_utilisateur', 'id_contenu']);
            
            // Index pour améliorer les performances
            $table->index('id_utilisateur');
            $table->index('id_contenu');
            $table->index('date_ajout');
        });
    }

    public function down()
    {
        Schema::dropIfExists('favoris');
    }
};