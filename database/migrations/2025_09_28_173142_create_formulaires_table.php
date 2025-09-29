<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormulairesTable extends Migration
{
    public function up()
    {
        Schema::create('formulaires', function (Blueprint $table) {
            $table->id();
            $table->string('name_Firma', 50);
            $table->string('name_Manager', 50);
            $table->string('land_Firma', 50);
            $table->string('name_Schuler', 50);
            $table->string('land_Schuler', 50);
            $table->date('date_in');
            $table->date('date_out');

            // Les blobs pour stocker les binaires
            $table->binary('sign_Manager');
            $table->binary('sign_Schuler');
            $table->binary('image_Schuler');

            // Les colonnes hash pour garantir l’unicité
            $table->string('sign_Manager_hash', 64)->unique();
            $table->string('sign_Schuler_hash', 64)->unique();
            $table->string('image_Schuler_hash', 64)->unique();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('formulaires');
    }
}
