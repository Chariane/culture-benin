<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            // Ajoutez ces colonnes si elles n'existent pas
            if (!Schema::hasColumn('paiements', 'metadata')) {
                $table->text('metadata')->nullable()->after('methode_paiement');
            }
            if (!Schema::hasColumn('paiements', 'statut')) {
                $table->string('statut', 50)->default('pending')->after('metadata');
            }
        });
    }

    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropColumn(['metadata', 'statut']);
        });
    }
};