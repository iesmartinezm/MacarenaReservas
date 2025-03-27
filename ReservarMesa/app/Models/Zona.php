<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    use HasFactory;

    protected $table = 'zonas';

    protected $fillable = ['nombre'];

    // Relación 1:N con Mesas
    public function mesas()
    {
        return $this->hasMany(Mesa::class, 'id_zona');
    }
}
