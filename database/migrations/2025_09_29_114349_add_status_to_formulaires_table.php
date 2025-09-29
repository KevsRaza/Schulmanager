<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToFormulairesTable extends Migration
{
    public function up()
    {
        Schema::table('formulaires', function (Blueprint $table) {
            $table->boolean('status')->default(false); // Ajoute la colonne 'status' avec une valeur par défaut de false
        });
    }

    public function down()
    {
        Schema::table('formulaires', function (Blueprint $table) {
            $table->dropColumn('status'); // Supprime la colonne 'status' lors du rollback
        });
    }
}

