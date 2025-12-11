<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Vérifions d'abord ce qui existe
        $columns = DB::select("SELECT column_name FROM information_schema.columns WHERE table_name = 'likes'");
        $columnNames = array_column($columns, 'column_name');
        
        // Si la table n'existe pas, créez-la
        if (!Schema::hasTable('likes')) {
            Schema::create('likes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_utilisateur');
                $table->unsignedBigInteger('id_contenu');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                
                $table->foreign('id_utilisateur')->references('id_utilisateur')->on('utilisateurs')->onDelete('cascade');
                $table->foreign('id_contenu')->references('id_contenu')->on('contenus')->onDelete('cascade');
                
                $table->unique(['id_utilisateur', 'id_contenu']);
            });
        } else {
            // Ajouter la colonne 'date' si elle n'existe pas
            if (!in_array('date', $columnNames)) {
                Schema::table('likes', function (Blueprint $table) {
                    $table->timestamp('date')->nullable()->after('id_contenu');
                });
            }
            
            // Ajouter created_at/updated_at s'ils n'existent pas
            if (!in_array('created_at', $columnNames)) {
                Schema::table('likes', function (Blueprint $table) {
                    $table->timestamp('created_at')->nullable()->after('date');
                });
            }
            
            if (!in_array('updated_at', $columnNames)) {
                Schema::table('likes', function (Blueprint $table) {
                    $table->timestamp('updated_at')->nullable()->after('created_at');
                });
            }
        }
    }

    public function down(): void
    {
        // Pas de rollback pour éviter de perdre des données
    }
};