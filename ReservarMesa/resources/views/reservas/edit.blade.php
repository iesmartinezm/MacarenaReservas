@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Editar Reserva</h2>

    <!-- Mensajes de éxito -->
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Mensajes de error -->
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @php
    $horarios = [];
    $inicio = strtotime('08:00');
    $fin = strtotime('23:00');
    while ($inicio <= $fin) {
        $horarios[]=date('H:i', $inicio);
        $inicio=strtotime('+30 minutes', $inicio);
        }
        @endphp

        <!-- Formulario para editar una reserva -->
        <div class="card mb-4">
            <div class="card-header">Editar Reserva</div>
            <div class="card-body">
                <form action="{{ route('reservas.update', $reserva->id) }}" method="POST" id="reservaForm">
                    @csrf
                    @method('PUT') <!-- Esto es importante para indicar que es una actualización -->

                    <div class="mb-3">
                        <label for="id_mesa" class="form-label">Mesa</label>
                        <select name="id_mesa" class="form-control" required>
                            <option value="">Selecciona una mesa</option>
                            @foreach($mesas as $mesa)
                            <option value="{{ $mesa->id }}" {{ $reserva->id_mesa == $mesa->id ? 'selected' : '' }}>
                                Mesa #{{ $mesa->id }} ({{ $mesa->num_sillas }} sillas)
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="{{ $reserva->usuario->nombre }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control" value="{{ $reserva->usuario->email }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_inicio" class="form-label">Hora de Inicio</label>
                        <select name="fecha_inicio" class="form-control" id="fecha_inicio" required>
                            <option value="">Selecciona una hora</option>
                            @foreach($horarios as $hora)
                            <option value="{{ now()->format('Y-m-d') . ' ' . $hora }}"
                                {{ $reserva->fecha_inicio == now()->format('Y-m-d') . ' ' . $hora ? 'selected' : '' }}>
                                {{ $hora }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_fin" class="form-label">Hora de Fin</label>
                        <select name="fecha_fin" class="form-control" id="fecha_fin" required>
                            <option value="">Selecciona una hora</option>
                            @foreach($horarios as $hora)
                            <option value="{{ now()->format('Y-m-d') . ' ' . $hora }}"
                                {{ $reserva->fecha_fin == now()->format('Y-m-d') . ' ' . $hora ? 'selected' : '' }}>
                                {{ $hora }}
                            </option>
                            @endforeach
                        </select>
                        <div id="error-message" class="text-danger mt-2" style="display: none;">La hora de fin debe ser posterior a la de inicio.</div>
                    </div>

                    <div class="mb-3">
                        <label for="num_comensales" class="form-label">Número de Comensales</label>
                        <input type="number" name="num_comensales" class="form-control" min="1" value="{{ $reserva->num_comensales }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary" id="submitBtn">Actualizar Reserva</button>
                </form>
            </div>
        </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const fechaInicio = document.getElementById("fecha_inicio");
        const fechaFin = document.getElementById("fecha_fin");
        const errorMessage = document.getElementById("error-message");
        const submitBtn = document.getElementById("submitBtn");

        function validarHoras() {
            if (fechaInicio.value && fechaFin.value) {
                const horaInicio = new Date(fechaInicio.value).getHours() * 60 + new Date(fechaInicio.value).getMinutes();
                const horaFin = new Date(fechaFin.value).getHours() * 60 + new Date(fechaFin.value).getMinutes();

                if (horaFin <= horaInicio) {
                    errorMessage.style.display = "block";
                    submitBtn.disabled = true;
                } else {
                    errorMessage.style.display = "none";
                    submitBtn.disabled = false;
                }
            }
        }

        fechaInicio.addEventListener("change", validarHoras);
        fechaFin.addEventListener("change", validarHoras);
    });
</script>
@endsection