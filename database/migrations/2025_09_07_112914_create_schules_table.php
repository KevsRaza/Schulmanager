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
        Schema::create('schules', function (Blueprint $table) {
            $table->id('id_Schule');
            $table->string('name_Schule', 100);
            $table->string('land_Schule', 50);
            $table->string('name_Schulleiter', 100);
            $table->foreignId('id_Dossier')->constrained('dossiers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schules');
    }
};