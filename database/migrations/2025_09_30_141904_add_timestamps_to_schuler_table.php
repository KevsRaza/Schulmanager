<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToSchulerTable extends Migration
{
    public function up()
    {
        Schema::table('schuler', function (Blueprint $table) {
            $table->timestamps(); // Ajoute created_at et updated_at
        });
    }

    public function down()
    {
        Schema::table('schuler', function (Blueprint $table) {
            $table->dropTimestamps(); // Supprime created_at et updated_at
        });
    }
}

