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
    Schema::create('avis', function (Blueprint $table) {
        $table->id('id_avis');
        $table->unsignedBigInteger('id_lecteur');
        $table->text('message');
        $table->date('date')->default(now());
        $table->timestamps();

        $table->foreign('id_lecteur')->references('id_utilisateur')->on('utilisateurs')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};
