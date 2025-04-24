@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Listado de Reservas</h2>

    <!-- Botón para crear una nueva reserva -->
    <a href="{{ route('reservas.create') }}" class="btn btn-primary mb-3">Crear Reserva</a>

    <!-- Mensajes de éxito -->
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Mensajes de error -->
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Tabla de Reservas -->
    <div class="card">
        <div class="card-header">Reservas Actuales</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mesa</th>
                        <th>Usuario</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Comensales</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservas as $reserva)
                    <tr>
                        <td>{{ $reserva->id }}</td>
                        <td>Mesa #{{ $reserva->mesa->id }} ({{ $reserva->mesa->num_sillas }} sillas)</td>
                        <td>{{ $reserva->usuario->email }}</td>
                        <td>{{ $reserva->fecha_inicio }}</td>
                        <td>{{ $reserva->fecha_fin }}</td>
                        <td>{{ $reserva->num_comensales }}</td>
                        <td>
                            <!-- Acciones eliminar y editar -->
                            <a href="{{ route('reservas.edit', $reserva->id) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection