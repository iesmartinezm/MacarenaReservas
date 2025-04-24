<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\Mesa;
use App\Models\Usuario;
use App\Models\Reserva;

class ReservaTest extends TestCase
{
    use RefreshDatabase;

    // Este método se ejecuta antes de cada prueba
    protected function setUp(): void
    {
        parent::setUp();

        // Ejecutar los seeders
        $this->seed(DatabaseSeeder::class);
    }

    /** @test */
    public function puede_acceder_al_listado_de_reservas()
    {
        // Acceder a la página de listado de reservas
        $response = $this->get(route('reservas.index'));

        // Comprobar que la respuesta es 200 OK
        $response->assertStatus(200);
    }

    /** @test */
    public function puede_crear_una_reserva()
    {
        // Crear una mesa manualmente
        $mesa = Mesa::create(['num_sillas' => 4, 'id_zona' => 1]);

        // Crear un usuario manualmente
        $usuario = Usuario::create([
            'nombre' => 'Carlos López',
            'email' => 'carlos@example.com',
        ]);

        // Definir los datos para la reserva (ajustando fechas)
        $data = [
            'id_mesa' => $mesa->id,
            'nombre' => 'Carlos López',
            'email' => 'carlos@example.com',
            'fecha_inicio' => now()->addDays(1)->setHour(14)->setMinute(0)->toDateTimeString(), // Hora específica
            'fecha_fin' => now()->addDays(1)->setHour(16)->setMinute(0)->toDateTimeString(),    // Hora específica
            'num_comensales' => 4,
        ];

        // Realizar la solicitud POST para crear la reserva
        $response = $this->post(route('reservas.store'), $data);

        // Verificar que la respuesta sea una redirección (al index)
        $response->assertStatus(302);
        $response->assertRedirect(route('reservas.index'));

        // Verificar que la reserva se haya guardado en la base de datos
        $this->assertDatabaseHas('reservas', [
            'id_mesa' => $mesa->id,
            'id_usuario' => $usuario->id,
            'num_comensales' => 4,
        ]);
    }

    /** @test */
    public function evita_solapar_las_horas_de_las_reservas()
    {
        // Crear una mesa manualmente
        $mesa = Mesa::create(['num_sillas' => 4, 'id_zona' => 1]);

        // Crear un usuario manualmente
        $usuario = Usuario::create([
            'nombre' => 'Carlos López',
            'email' => 'carlos@example.com',
        ]);

        // Crear una reserva existente para esa mesa
        Reserva::create([
            'id_mesa' => $mesa->id,
            'id_usuario' => $usuario->id,
            'fecha_inicio' => now()->addDays(1)->setHour(14)->setMinute(0)->toDateTimeString(),
            'fecha_fin' => now()->addDays(1)->setHour(16)->setMinute(0)->toDateTimeString(),
            'num_comensales' => 4,
        ]);

        // Intentar crear una nueva reserva para la misma mesa en el mismo horario
        $data = [
            'id_mesa' => $mesa->id,
            'nombre' => 'Carlos López',
            'email' => 'carlos@example.com',
            'fecha_inicio' => now()->addDays(1)->setHour(15)->setMinute(0)->toDateTimeString(), // Hora después de la primera
            'fecha_fin' => now()->addDays(1)->setHour(17)->setMinute(0)->toDateTimeString(),    // Hora posterior
            'num_comensales' => 4,
        ];

        // Realizar la solicitud
        $response = $this->post(route('reservas.store'), $data);

        // Verificar que la respuesta redirija al listado de reservas y que se muestre el mensaje de error
        $response->assertStatus(302); // Redirección
        $response->assertSessionHas('error', 'Esa mesa ya está reservada en ese horario.'); // El mensaje de error debe ser el mismo que la validación
    }

    /** @test */
    public function puede_acceder_a_la_pagina_de_reservas()
    {
        // Acceder a la página de creación de reserva
        $response = $this->get(route('reservas.create'));

        // Comprobar que la respuesta es 200 OK
        $response->assertStatus(200);
    }

    /** @test */
    public function puede_editar_una_reserva()
    {
        // Crear una mesa manualmente
        $mesa = Mesa::create(['num_sillas' => 4, 'id_zona' => 1]);

        // Crear un usuario manualmente
        $usuario = Usuario::create([
            'nombre' => 'Carlos López',
            'email' => 'carlos@example.com',
        ]);

        // Crear una reserva manualmente
        $reserva = Reserva::create([
            'id_mesa' => $mesa->id,
            'id_usuario' => $usuario->id,
            'fecha_inicio' => now()->addDays(1)->setHour(14)->setMinute(0)->toDateTimeString(),
            'fecha_fin' => now()->addDays(1)->setHour(16)->setMinute(0)->toDateTimeString(),
            'num_comensales' => 4,
        ]);

        // Datos de actualización
        $data = [
            'id_mesa' => $mesa->id,
            'nombre' => 'Carlos López',
            'email' => 'carlos@example.com',
            'fecha_inicio' => now()->addDays(2)->setHour(15)->setMinute(0)->toDateTimeString(),
            'fecha_fin' => now()->addDays(2)->setHour(17)->setMinute(0)->toDateTimeString(),
            'num_comensales' => 5,
        ];

        // Realizar la solicitud PUT para editar la reserva
        $response = $this->put(route('reservas.update', $reserva->id), $data);

        // Verificar que la reserva se haya actualizado en la base de datos
        $this->assertDatabaseHas('reservas', [
            'id_mesa' => $mesa->id,
            'id_usuario' => $usuario->id,
            'num_comensales' => 5,
        ]);
    }

    /** @test */
    public function puede_eliminar_una_reserva()
    {
        // Crear una mesa manualmente
        $mesa = Mesa::create(['num_sillas' => 4, 'id_zona' => 1]);

        // Crear un usuario manualmente
        $usuario = Usuario::create([
            'nombre' => 'Carlos López',
            'email' => 'carlos@example.com',
        ]);

        // Crear una reserva manualmente
        $reserva = Reserva::create([
            'id_mesa' => $mesa->id,
            'id_usuario' => $usuario->id,
            'fecha_inicio' => now()->addDays(1)->setHour(14)->setMinute(0)->toDateTimeString(),
            'fecha_fin' => now()->addDays(1)->setHour(16)->setMinute(0)->toDateTimeString(),
            'num_comensales' => 4,
        ]);

        // Realizar la solicitud DELETE para eliminar la reserva
        $response = $this->delete(route('reservas.destroy', $reserva->id));

        // Verificar que la reserva se haya eliminado de la base de datos
        $this->assertDatabaseMissing('reservas', [
            'id_mesa' => $mesa->id,
            'id_usuario' => $usuario->id,
        ]);
    }
}
