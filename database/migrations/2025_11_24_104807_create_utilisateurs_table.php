<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id('id_utilisateur');
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('mot_de_passe');
            $table->enum('sexe', ['Homme', 'Femme'])->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('photo')->nullable();
            
            // Relations
            $table->unsignedBigInteger('id_role');
            $table->unsignedBigInteger('id_langue');

            // Champs supplémentaires
            $table->dateTime('date_inscription')->nullable();
            $table->enum('statut', ['actif', 'inactif'])->default('actif');

            $table->timestamps();

            // Clés étrangères
            $table->foreign('id_role')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('id_langue')->references('id_langue')->on('langues')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};
