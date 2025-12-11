<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contenu_medias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_contenu');
            $table->string('fichier');
            $table->string('type')->nullable(); // image / video / audio
            $table->timestamps();

            $table->foreign('id_contenu')
                ->references('id_contenu')
                ->on('contenus')
                ->onDelete('cascade'); // si tu veux empêcher, je change
        });
    }

    public function down(): void
    {
        Schema::table('contenus', function (Blueprint $table) {
            $table->string('media')->nullable();
        });
    }
};
