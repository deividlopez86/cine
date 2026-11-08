<?php

namespace Tests\Feature;

use App\Models\Funciones;
use App\Models\Peliculas;
use App\Models\Salas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class FuncionesCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_can_manage_functions(): void
    {
        $user = User::factory()->create();
        $funcion = Funciones::factory()->create();

        $this->actingAs($user)
            ->get(route('funciones.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Catalogos/Index')
                ->where('resource', 'funciones')
                ->has('records.data', 1));

        $this->actingAs($user)->put(route('funciones.update', $funcion), [
            'pelicula_id' => $funcion->pelicula_id,
            'sala_id' => $funcion->sala_id,
            'fecha' => '2030-05-10',
            'hora_inicio' => '12:00',
            'hora_fin' => '14:00',
            'precio' => '12000.00',
            'estado' => 1,
        ])->assertRedirect(route('funciones.index'));

        $this->assertDatabaseHas('funciones', [
            'id' => $funcion->id,
            'fecha' => '2030-05-10',
            'precio' => '12000.00',
        ]);

        $this->actingAs($user)->get(route('funciones.show', $funcion))->assertOk();
        $this->actingAs($user)->get(route('funciones.edit', $funcion))->assertOk();

        $this->actingAs($user)->delete(route('funciones.destroy', $funcion))
            ->assertRedirect(route('funciones.index'));

        $this->assertDatabaseMissing('funciones', ['id' => $funcion->id]);
    }

    public function test_active_functions_cannot_overlap_in_the_same_room(): void
    {
        $movie = Peliculas::factory()->create();
        $room = Salas::factory()->create();
        Funciones::factory()->create([
            'pelicula_id' => $movie->id,
            'sala_id' => $room->id,
            'fecha' => '2030-05-10',
            'hora_inicio' => '10:00:00',
            'hora_fin' => '12:00:00',
            'estado' => true,
        ]);

        $this->actingAs(User::factory()->create())
            ->post(route('funciones.store'), [
                'pelicula_id' => $movie->id,
                'sala_id' => $room->id,
                'fecha' => '2030-05-10',
                'hora_inicio' => '11:30',
                'hora_fin' => '13:00',
                'precio' => 10000,
                'estado' => 1,
            ])
            ->assertSessionHasErrors('hora_inicio');

        $this->assertDatabaseCount('funciones', 1);
    }

    public function test_function_routes_require_authentication(): void
    {
        $this->get(route('funciones.index'))->assertRedirect('/login');
        $this->get(route('funciones.create'))->assertRedirect('/login');
    }
}
