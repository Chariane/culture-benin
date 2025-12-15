<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_contenu');
            $table->unsignedBigInteger('id_utilisateur');
            $table->date('date')->nullable();
            
            $table->timestamps();

            // Clés étrangères
            $table->foreign('id_contenu')
                ->references('id_contenu')
                ->on('contenus')
                ->onDelete('cascade');

            $table->foreign('id_utilisateur')
                ->references('id_utilisateur')
                ->on('utilisateurs')
                ->onDelete('cascade');

            // Empêcher les doublons
            $table->unique(['id_contenu', 'id_utilisateur']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('views');
    }
};