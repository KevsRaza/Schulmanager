<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Land extends Model
{
    use HasFactory;

    protected $table = 'land';
    protected $primaryKey = 'idLand';
    public $timestamps = false;

    protected $fillable = ['nameLand'];

    public function schulen()
    {
        return $this->hasMany(Schule::class, 'idLand', 'idLand');
    }
}
