<?php

use App\Http\Controllers\ReservaController;
use Illuminate\Support\Facades\Route;

// Formulario para crear una nueva reserva
Route::get('/reservas/create', [ReservaController::class, 'create'])->name('reservas.create');
Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');

// Mostrar la lista de reservas
Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.index');

// Editar una reserva
Route::get('/reservas/{id}/edit', [ReservaController::class, 'edit'])->name('reservas.edit');
Route::put('/reservas/{id}', [ReservaController::class, 'update'])->name('reservas.update');

// Eliminar una reserva
Route::delete('/reservas/{id}', [ReservaController::class, 'destroy'])->name('reservas.destroy');
