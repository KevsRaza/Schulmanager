<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schuler extends Model
{
    protected $table = 'schuler';
    protected $primaryKey = 'id_Schuler';

    protected $casts = [
        'geburtsdatum_Schuler' => 'date',
        'datum_Anfang_Ausbildung' => 'date',
        'datum_Ende_Ausbildung' => 'date',
    ];


    protected $fillable = [
        'vorname',
        'familiename',
        'geburtsdatum_Schuler',
        'land_Schuler',
        'deutschniveau_Schuler',
        'bildungsniveau_Schuler',
        'datum_Anfang_Ausbildung',
        'datum_Ende_Ausbildung',
        'email',
        'id_Firma',
        'id_Schule',
        'id_Dokument',
        'idAutorite',
    ];

    public function schule()
    {
        return $this->belongsTo(Schule::class, 'id_Schule', 'id_Schule');
    }

    public function firma()
    {
        return $this->belongsTo(Firma::class, 'id_Firma', 'id_Firma');
    }

    public function dokument()
    {
        return $this->belongsTo(Dokument::class, 'id_Dokument', 'id_dokument');
    }

    public function autorites()
    {
        return $this->belongsTo(Autorites::class, 'idAutorite', 'idAutorite');
    }

    public function land()
    {
        return $this->belongsTo(Land::class, 'idLand', 'idLand');
    }

    // Méthode pour obtenir le nom complet de l'élève
    public function getFullNameAttribute()
    {
        return "{$this->vorname} {$this->familiename}";
    }
}
