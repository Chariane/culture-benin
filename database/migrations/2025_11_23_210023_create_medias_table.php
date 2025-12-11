<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->id('id_media');
            $table->string('chemin');
            $table->text('description')->nullable();

            $table->unsignedBigInteger('id_type_media');
            $table->foreign('id_type_media')
                ->references('id_type_media')
                ->on('type_medias')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medias');
    }
};
