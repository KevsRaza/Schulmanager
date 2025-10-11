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
        'name_Schulleiter'
    ];

    public function land(): BelongsTo
    {
        return $this->belongsTo(Land::class, 'idLand');
    }
}