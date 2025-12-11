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
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->string('google2fa_secret')->nullable()->after('mot_de_passe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            //
        });
    }
};
