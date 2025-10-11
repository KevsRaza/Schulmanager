<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autorites extends Model
{
    protected $table = 'autorites';
    protected $primaryKey = 'idAutorite';

    protected $fillable  = [
        'typeProcedure',
    ];

    public function autorites()
    {
        return $this->hasMany(Schuler::class, 'id_Schuler', 'id_Schuler');
    }
}
