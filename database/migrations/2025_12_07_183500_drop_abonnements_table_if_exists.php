<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Supprimer la table si elle existe (méthode compatible tous drivers)
        Schema::dropIfExists('abonnements');
    }

    public function down()
    {
        // Recréer la table vide pour le rollback
        Schema::create('abonnements', function (Blueprint $table) {
            $table->unsignedBigInteger('id_auteur');
            $table->unsignedBigInteger('id_abonne');
            $table->timestamp('date_abonnement')->useCurrent();
            $table->boolean('notifications_actives')->default(true);
            $table->timestamps();
            $table->primary(['id_auteur', 'id_abonne']);
        });
    }
};