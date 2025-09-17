<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ausbildung extends Model
{
    protected $table = 'ausbildung'; // Table allemande
    protected $primaryKey = 'id_Ausbildung';
    public $timestamps = false; // Pas de created_at/updated_at

    protected $fillable = [
        'name_Ausbildung',
        'id_Dossier'
    ];

    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class, 'id_Dossier');
    }
}