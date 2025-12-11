<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // supprimer l’ancienne contrainte
        DB::statement('ALTER TABLE contenus DROP CONSTRAINT contenus_id_type_contenu_foreign');

        // recréer en RESTRICT
        DB::statement('ALTER TABLE contenus 
            ADD CONSTRAINT contenus_id_type_contenu_foreign
            FOREIGN KEY (id_type_contenu)
            REFERENCES type_contenus(id_type_contenu)
            ON DELETE RESTRICT');
    }

    public function down()
    {
        DB::statement('ALTER TABLE contenus DROP CONSTRAINT contenus_id_type_contenu_foreign');

        DB::statement('ALTER TABLE contenus 
            ADD CONSTRAINT contenus_id_type_contenu_foreign
            FOREIGN KEY (id_type_contenu)
            REFERENCES type_contenus(id_type_contenu)');
    }
};
