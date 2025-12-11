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
        Schema::table('contenus', function (Blueprint $table) {
            $table->integer('likes')->nullable()->default(0)->after('texte');
            // Vous pouvez aussi ajouter un champ pour les vues
            $table->integer('views')->nullable()->default(0)->after('likes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contenus', function (Blueprint $table) {
            $table->dropColumn(['likes', 'views']);
        });
    }
};