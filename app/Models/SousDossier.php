<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SousDossier extends Model
{
    protected $table = 'sous_dossiers';
    protected $primaryKey = 'id_SousDossier';
    protected $fillable = ['name_SousDossier', 'id_Dossier'];

    public function dossier()
    {
        return $this->belongsTo(Dossier::class, 'id_Dossier', 'id_Dossier');
    }
}
