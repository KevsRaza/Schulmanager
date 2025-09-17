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
        Schema::create('schulers', function (Blueprint $table) {
            $table->id('id_Schuler');
            $table->string('vorname', 50)->nullable();
            $table->string('familiename', 50);
            $table->date('geburtsdatum_Schuler');
            $table->string('land_Shuler', 50);
            $table->enum('deutschniveau_Schuler', ['A1', 'A2', 'B1', 'B2', 'C1', 'C2']);
            $table->enum('bildungsniveau_Schuler', ['Primaire', 'Secondaire', 'Universitaire', 'Professionnel']);
            $table->date('datum_Anfang_Ausbildung');
            $table->date('datum_Ende_Ausbildung');
            $table->string('email', 100)->unique();
            $table->foreignId('id_Firma')->constrained('firmas');
            $table->foreignId('id_Schule')->constrained('schules');
            $table->foreignId('id_Ausbildung')->constrained('ausbildungs');
            $table->foreignId('id_Dokument')->constrained('dokuments');
            $table->foreignId('id_Dossier')->constrained('dossiers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schulers');
    }
};