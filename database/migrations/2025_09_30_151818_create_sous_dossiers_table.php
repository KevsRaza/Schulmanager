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
        Schema::create('dossier_sous_dossier', function (Blueprint $table) {
            $table->unsignedBigInteger('id_Dossier');
            $table->unsignedBigInteger('id_SousDossier');

            $table->foreign('id_Dossier')->references('id_Dossier')->on('dossier')->onDelete('cascade');
            $table->foreign('id_SousDossier')->references('id_SousDossier')->on('sous_dossiers')->onDelete('cascade');

            $table->primary(['id_Dossier', 'id_SousDossier']); // clé primaire composite
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sous_dossiers');
    }
};
