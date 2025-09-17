<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    protected $table = 'dossier';
    protected $primaryKey = 'id_Dossier';

    public function dokumente()
    {
        return $this->hasMany(Dokument::class, 'id_Dossier', 'id_Dossier');
    }

    public function ausbildungen()
    {
        return $this->hasMany(Ausbildung::class, 'id_Dossier', 'id_Dossier');
    }

    public function schulen()
    {
        return $this->hasMany(Schule::class, 'id_Dossier', 'id_Dossier');
    }

    public function firmen()
    {
        return $this->hasMany(Firma::class, 'id_Dossier', 'id_Dossier');
    }

    public function schulers()
    {
        return $this->hasMany(Schuler::class, 'id_Dossier', 'id_Dossier');
    }
}