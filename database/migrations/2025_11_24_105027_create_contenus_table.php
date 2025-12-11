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
    Schema::create('contenus', function (Blueprint $table) {
        $table->id('id_contenu');
        $table->string('titre');
        $table->longText('texte');
        $table->date('date_creation')->nullable();
        $table->string('statut')->nullable();
        $table->unsignedBigInteger('parent_id')->nullable();
        $table->date('date_validation')->nullable();

        // FK
        $table->unsignedBigInteger('id_region');
        $table->unsignedBigInteger('id_langue');
        $table->unsignedBigInteger('id_moderateur');
        $table->unsignedBigInteger('id_type_contenu');
        $table->unsignedBigInteger('id_auteur');

        $table->foreign('id_region')->references('id_region')->on('regions');
        $table->foreign('id_langue')->references('id_langue')->on('langues');
        $table->foreign('id_moderateur')->references('id_utilisateur')->on('utilisateurs');
        $table->foreign('id_type_contenu')->references('id_type_contenu')->on('type_contenus');
        $table->foreign('id_auteur')->references('id_utilisateur')->on('utilisateurs');
        $table->foreign('parent_id')->references('id_contenu')->on('contenus')->nullOnDelete();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contenus');
    }
};
