<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Mesa;
use App\Models\Usuario;

class ReservaController extends Controller
{
    public function index()
    {
        $reservas = Reserva::with('mesa', 'usuario')->get();
        $mesas = Mesa::all();
        $usuarios = Usuario::all();

        return view('reservas.index', compact('reservas', 'mesas', 'usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'id_mesa' => 'required|exists:mesas,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'num_comensales' => 'required|integer|min:1',
        ]);

        // Comprobar si la mesa está ocupada en la fecha y hora seleccionada
        $mesaOcupada = Reserva::where('id_mesa', $request->id_mesa)
            ->where(function ($query) use ($request) {
                $query->whereBetween('fecha_inicio', [$request->fecha_inicio, $request->fecha_fin])
                    ->orWhereBetween('fecha_fin', [$request->fecha_inicio, $request->fecha_fin]);
            })->exists();

        if ($mesaOcupada) {
            return redirect()->route('reservas.index')->with('error', 'Esa mesa ya está reservada en ese horario.');
        }

        // Crear usuario si no existe
        $usuario = Usuario::firstOrCreate([
            'email' => $request->email
        ], [
            'nombre' => $request->nombre
        ]);

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

    public function destroy($id)
    {
        Reserva::findOrFail($id)->delete();

        return redirect()->route('reservas.index')->with('success', 'Reserva eliminada.');
    }
}
