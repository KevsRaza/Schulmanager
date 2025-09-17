<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schule extends Model
{
    protected $table = 'schule'; // Table allemande
    protected $primaryKey = 'id_Schule';
    public $timestamps = false;

    protected $fillable = [
        'name_Schule',
        'land_Schule',
        'name_Schulleiter',
        'id_Dossier'
    ];

    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class, 'id_Dossier');
    }
}