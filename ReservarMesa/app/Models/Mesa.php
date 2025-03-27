<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    use HasFactory;

    protected $table = 'mesas';

    protected $fillable = ['num_sillas', 'id_zona'];

    // Relación N:1 con Zona
    public function zona()
    {
        return $this->belongsTo(Zona::class, 'id_zona');
    }

    // Relación 1:N con Reservas
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id_mesa');
    }
}
