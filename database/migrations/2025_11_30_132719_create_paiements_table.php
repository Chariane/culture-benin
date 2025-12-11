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
    Schema::create('paiements', function (Blueprint $table) {
        $table->id('id_paiement');
        $table->unsignedBigInteger('id_contenu');
        $table->unsignedBigInteger('id_lecteur');
        $table->string('id_transaction')->nullable(); 
        $table->decimal('montant', 10, 2);
        $table->string('methode_paiement')->default('fedapay');
        $table->string('statut')->default('pending'); // pending, success, failed
        $table->json('metadata')->nullable();
        $table->timestamp('date_paiement')->useCurrent();
        $table->timestamps();

        $table->foreign('id_contenu')->references('id_contenu')->on('contenus')->cascadeOnDelete();
        $table->foreign('id_lecteur')->references('id_utilisateur')->on('utilisateurs')->cascadeOnDelete();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
