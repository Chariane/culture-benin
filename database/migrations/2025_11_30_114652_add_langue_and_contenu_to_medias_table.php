<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medias', function (Blueprint $table) {

            // Ajouter id_langue
            $table->unsignedBigInteger('id_langue')->after('id_type_media');
            $table->foreign('id_langue')
                ->references('id_langue')
                ->on('langues')
                ->onDelete('cascade');

            // Ajouter id_contenu
            $table->unsignedBigInteger('id_contenu')->after('id_langue');
            $table->foreign('id_contenu')
                ->references('id_contenu')
                ->on('contenus')
                ->onDelete('cascade');

            // Modifier le champ chemin en text
            $table->text('chemin')->change();
        });
    }

    public function down(): void
    {
        Schema::table('medias', function (Blueprint $table) {

            $table->dropForeign(['id_langue']);
            $table->dropColumn('id_langue');

            $table->dropForeign(['id_contenu']);
            $table->dropColumn('id_contenu');

            $table->string('chemin')->change();
        });
    }
};
