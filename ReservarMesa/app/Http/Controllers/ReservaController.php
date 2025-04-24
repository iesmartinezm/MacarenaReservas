<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Mesa;
use App\Models\Usuario;

class ReservaController extends Controller
{
    // Muestra el listado de reservas
    public function index()
    {
        // Obtener las mesas disponibles
        $mesas = Mesa::all();

        // Obtiene las reservas junto con las mesas y usuarios relacionados
        $reservas = Reserva::with('mesa', 'usuario')->get();

        // Retorna la vista 'reservas.index' con las reservas y mesas obtenidas
        return view('reservas.index', compact('reservas', 'mesas'));
    }

    // Muestra el formulario de creación de reserva
    public function create()
    {
        // Se obtienen todas las mesas disponibles para la creación
        $mesas = Mesa::all();
        return view('reservas.create', compact('mesas'));
    }

    // Muestra el formulario de edición
    public function edit($id)
    {
        $reserva = Reserva::findOrFail($id);
        $mesas = Mesa::all();
        return view('reservas.edit', compact('reserva', 'mesas'));
    }

    // Actualiza una reserva existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_mesa' => 'required|exists:mesas,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'num_comensales' => 'required|integer|min:1',
        ]);

        $reserva = Reserva::findOrFail($id);
        $reserva->update([
            'id_mesa' => $request->id_mesa,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'num_comensales' => $request->num_comensales,
        ]);

        return redirect()->route('reservas.index')->with('success', 'Reserva actualizada correctamente.');
    }

    // Guarda una nueva reserva en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email',
            'id_mesa' => 'required|exists:mesas,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'num_comensales' => 'required|integer|min:1',
        ]);

        // Comprobar si la mesa está ocupada en el rango de fechas
        $mesaOcupada = Reserva::where('id_mesa', $request->id_mesa)
            ->where(function ($query) use ($request) {
                $query->where('fecha_inicio', '<', $request->fecha_fin)
                    ->where('fecha_fin', '>', $request->fecha_inicio);
            })
            ->exists();

        if ($mesaOcupada) {
            return redirect()->route('reservas.index')->with('error', 'Esa mesa ya está reservada en ese horario.');
        }

        // Crear usuario si no existe
        $usuario = Usuario::firstOrCreate(
            ['email' => $request->email],
            ['nombre' => $request->nombre]
        );

        // Crear la reserva
        Reserva::create([
            'id_mesa' => $request->id_mesa,
            'id_usuario' => $usuario->id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'num_comensales' => $request->num_comensales,
        ]);

        return redirect()->route('reservas.index')->with('success', 'Reserva creada correctamente.');
    }

    // Eliminar una reserva
    public function destroy($id)
    {
        Reserva::findOrFail($id)->delete();
        return redirect()->route('reservas.index')->with('success', 'Reserva eliminada.');
    }
}
