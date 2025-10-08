<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sous_dossiers', function (Blueprint $table) {
            $table->increments('id_SousDossier'); // clé primaire
            $table->string('name_SousDossier');
            $table->unsignedBigInteger('id_Dossier'); // clé étrangère
            $table->timestamps();

            $table->foreign('id_Dossier')
                ->references('id_Dossier')
                ->on('dossier')
                ->onDelete('cascade'); // supprime les sous-dossiers si dossier supprimé
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sous_dossiers');
    }
};
