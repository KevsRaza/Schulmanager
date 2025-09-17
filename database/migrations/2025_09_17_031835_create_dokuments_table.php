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
        Schema::create('dokuments', function (Blueprint $table) {
            $table->id('id_dokument');
            $table->string('name_Dokument', 50);
            $table->string('dokumentTyp', 50);
            $table->string('weg_Dokument', 255);
            $table->foreignId('id_Dossier')->constrained('dossiers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokuments');
    }
};