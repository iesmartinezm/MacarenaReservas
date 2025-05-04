<?php

namespace Tests\Feature;

use App\Models\Mesa;
use App\Models\Reserva;
use App\Models\Usuario;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

// 1. Test acceso al listado de reservas
it('Accede al listado de reservas', function () {
    $response = $this->get(route('reservas.index'));
    $response->assertStatus(200);
});

// 2. Test acceso a creación de reservas
it('Accede a vista de creación de reserva', function () {
    $response = $this->get(route('reservas.create'));
    $response->assertStatus(200);
});

// 3. Test creación de reserva válida
it('Crea reserva válida y guarda en BD', function () {
    $mesa = Mesa::create(['num_sillas' => 4, 'id_zona' => 1]);
    $usuario = Usuario::create([
        'nombre' => 'Carlos López',
        'email' => 'carlos@example.com',
    ]);

    $data = [
        'id_mesa' => $mesa->id,
        'nombre' => 'Carlos López',
        'email' => 'carlos@example.com',
        'fecha_inicio' => now()->addDays(1)->setHour(14),
        'fecha_fin' => now()->addDays(1)->setHour(16),
        'num_comensales' => 4,
    ];

    $response = $this->post(route('reservas.store'), $data);

    $response->assertRedirect(route('reservas.index'));
    $this->assertDatabaseHas('reservas', [
        'id_mesa' => $mesa->id,
        'id_usuario' => $usuario->id,
        'num_comensales' => 4
    ]);
});

// 4. Test validación de reserva inválida
it('No crea reserva con datos inválidos', function () {
    $response = $this->post(route('reservas.store'), []);

    $response->assertSessionHasErrors([
        'id_mesa',
        'nombre',
        'email',
        'fecha_inicio',
        'fecha_fin',
        'num_comensales'
    ]);
});

// 5. Test solapamiento de reservas
it('Evita solapamiento de horarios', function () {
    $mesa = Mesa::create(['num_sillas' => 4, 'id_zona' => 1]);
    $usuario = Usuario::create([
        'nombre' => 'Carlos López',
        'email' => 'carlos@example.com',
    ]);

    Reserva::create([
        'id_mesa' => $mesa->id,
        'id_usuario' => $usuario->id,
        'fecha_inicio' => now()->addDays(1)->setHour(14),
        'fecha_fin' => now()->addDays(1)->setHour(16),
        'num_comensales' => 4,
    ]);

    $data = [
        'id_mesa' => $mesa->id,
        'nombre' => 'Carlos López',
        'email' => 'carlos@example.com',
        'fecha_inicio' => now()->addDays(1)->setHour(15),
        'fecha_fin' => now()->addDays(1)->setHour(17),
        'num_comensales' => 4,
    ];

    $response = $this->post(route('reservas.store'), $data);

    $response->assertSessionHas('error');
});

// 6. Test eliminación de reserva
it('Elimina una reserva correctamente', function () {
    $mesa = Mesa::create(['num_sillas' => 4, 'id_zona' => 1]);
    $usuario = Usuario::create([
        'nombre' => 'Carlos López',
        'email' => 'carlos@example.com',
    ]);

    $reserva = Reserva::create([
        'id_mesa' => $mesa->id,
        'id_usuario' => $usuario->id,
        'fecha_inicio' => now()->addDays(1)->setHour(14),
        'fecha_fin' => now()->addDays(1)->setHour(16),
        'num_comensales' => 4,
    ]);

    $response = $this->delete(route('reservas.destroy', $reserva->id));

    $this->assertDatabaseMissing('reservas', ['id' => $reserva->id]);
});
