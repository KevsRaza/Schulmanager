<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schuler extends Model
{
    protected $table = 'schuler';
    protected $primaryKey = 'id_Schuler';

    protected $fillable = [
        'vorname', 'familiename', 'geburtsdatum_Schuler', 'land_Shuler',
        'deutschniveau_Schuler', 'bildungsniveau_Schuler', 'datum_Anfang_Ausbildung',
        'datum_Ende_Ausbildung', 'id_Firma', 'id_Schule', 'id_Ausbildung',
        'id_Dokument', 'id_Dossier', 'email'
    ];

    public function schule()
    {
        return $this->belongsTo(Schule::class, 'id_Schule', 'id_Schule');
    }

    public function firma()
    {
        return $this->belongsTo(Firma::class, 'id_Firma', 'id_Firma');
    }

    public function ausbildung()
    {
        return $this->belongsTo(Ausbildung::class, 'id_Ausbildung', 'id_Ausbildung');
    }

    public function dokument()
    {
        return $this->belongsTo(Dokument::class, 'id_Dokument', 'id_dokument');
    }

    public function dossier()
    {
        return $this->belongsTo(Dossier::class, 'id_Dossier', 'id_Dossier');
    }
}