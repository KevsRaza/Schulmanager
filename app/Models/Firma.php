<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Firma extends Model
{
    protected $table = 'firma'; // Table allemande
    protected $primaryKey = 'id_Firma';
    public $timestamps = false;

    protected $fillable = [
        'name_Firma',
        'manager_Firma',
        'logo_Firma',
        'idAutorite',
        'idLand'
    ];

    public function land()
    {
        return $this->hasMany(Land::class, 'idLand', 'idLand');
    }
}