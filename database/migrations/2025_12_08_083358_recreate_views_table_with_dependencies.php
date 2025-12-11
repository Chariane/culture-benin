<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Supprimer la vue matérialisée si elle existe (uniquement pour PostgreSQL)
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP MATERIALIZED VIEW IF EXISTS vue_statistiques_contenu');
        }
        
        // 2. Supprimer la table existante si elle existe
        Schema::dropIfExists('views');
        
        // 3. Recréer la table avec la bonne structure
        Schema::create('views', function (Blueprint $table) {
            $table->id('id_view');
            $table->unsignedBigInteger('id_contenu');
            $table->unsignedBigInteger('id_utilisateur');
            $table->dateTime('date')->nullable();
            $table->integer('progression')->default(0);
            $table->boolean('termine')->default(false);
            $table->timestamp('derniere_lecture')->nullable();
            
            $table->timestamps(); // created_at et updated_at

            // Clés étrangères
            $table->foreign('id_contenu')
                ->references('id_contenu')
                ->on('contenus')
                ->onDelete('cascade');

            $table->foreign('id_utilisateur')
                ->references('id_utilisateur')
                ->on('utilisateurs')
                ->onDelete('cascade');

            // Empêcher les doublons - un utilisateur ne peut avoir qu'une entrée par contenu
            $table->unique(['id_contenu', 'id_utilisateur']);
        });

        // 4. Recréer la vue matérialisée si nécessaire
        // Nous devons savoir quelle était sa définition. 
        // Si vous n'avez pas la définition, vous pouvez la recréer plus tard.
        // Pour l'instant, nous allons laisser un commentaire pour la recréer manuellement.
        // Si vous avez la définition, vous pouvez l'exécuter ici avec DB::statement.
    }

    public function down()
    {
        // En cas de rollback, nous supprimons la table et la vue matérialisée.
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP MATERIALIZED VIEW IF EXISTS vue_statistiques_contenu');
        }
        Schema::dropIfExists('views');
        
        // Note: Si vous aviez une vue matérialisée avant, vous devriez la recréer dans le down() aussi.
        // Mais comme nous ne connaissons pas sa définition, nous ne le faisons pas ici.
    }
};