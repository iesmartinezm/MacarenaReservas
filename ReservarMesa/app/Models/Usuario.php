<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = ['email', 'id_rol'];

    // Relación N:1 con Rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }

    // Relación 1:N con Reservas
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id_usuario');
    }
}
