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
        Schema::create('dossier_sous_dossier', function (Blueprint $table) {
            $table->id(); // id pivot

            $table->integer('id_Dossier');           // correspond à dossier.id_Dossier
            $table->unsignedBigInteger('id_SousDossier'); // correspond à sous_dossiers.id_SousDossier

            $table->foreign('id_Dossier')
                ->references('id_Dossier')->on('dossier')
                ->onDelete('cascade');

            $table->foreign('id_SousDossier')
                ->references('id_SousDossier')->on('sous_dossiers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossier_sous_dossier');
    }
};
