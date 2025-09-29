<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formulaire extends Model
{
    protected $table = 'formulaires'; // nom de la table
    protected $fillable = [
        'name_Firma',
        'name_Manager',
        'land_Firma',
        'name_Schuler',
        'land_Schuler',
        'date_in',
        'date_out',
        'sign_Manager',
        'sign_Manager_hash',
        'sign_Schuler',
        'sign_Schuler_hash',
        'image_Schuler',
        'image_Schuler_hash',
        'status',
    ];
}
