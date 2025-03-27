<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reservas';

    protected $fillable = ['id_mesa', 'id_usuario', 'fecha_inicio', 'fecha_fin', 'num_comensales'];

    // Relación N:1 con Mesa
    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'id_mesa');
    }

    // Relación N:1 con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
