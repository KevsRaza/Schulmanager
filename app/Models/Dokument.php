<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dokument extends Model
{
    protected $table = 'dokument'; // Table allemande
    protected $primaryKey = 'id_dokument';
    public $timestamps = false;

    protected $fillable = [
        'nameVisa',
    ];
}