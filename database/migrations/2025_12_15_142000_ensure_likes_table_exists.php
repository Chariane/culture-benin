<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Vérifier si la table 'likes' existe
        if (!Schema::hasTable('likes')) {
            Schema::create('likes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_contenu');
                $table->unsignedBigInteger('id_utilisateur');
                $table->timestamp('date')->nullable();
                $table->timestamps();

                $table->foreign('id_contenu')->references('id_contenu')->on('contenus')->onDelete('cascade');
                $table->foreign('id_utilisateur')->references('id_utilisateur')->on('utilisateurs')->onDelete('cascade');

                $table->unique(['id_contenu', 'id_utilisateur']);
            });
        }
        
        // 2. Nettoyage de la table 'views' CReeE PAR ERREUR (si elle a la structure de likes)
        // La mauvaise migration créait 'views' avec id_contenu/id_utilisateur.
        // La VRAIE table 'views' (si elle existe pour les stats) a probablement une autre structure ou n'est pas gérée ici.
        // Dans le doute, on ne supprime PAS 'views' pour ne pas perdre de vraies données, 
        // mais on s'assure que 'likes' est bien là.
    }

    public function down()
    {
        // Pas de rollback
    }
};
