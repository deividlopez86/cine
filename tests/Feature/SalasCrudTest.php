<?php

namespace Tests\Feature;

use App\Models\Funciones;
use App\Models\Salas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class SalasCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_can_manage_rooms(): void
    {
        $user = User::factory()->create();
        $room = Salas::factory()->create();

        $this->actingAs($user)
            ->get(route('salas.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Catalogos/Index')
                ->where('resource', 'salas')
                ->has('records.data', 1));

        $this->actingAs($user)->put(route('salas.update', $room), [
            'nombre' => 'Sala renovada',
            'capacidad' => 120,
            'estado' => 1,
        ])->assertRedirect(route('salas.index'));

        $this->assertDatabaseHas('salas', [
            'id' => $room->id,
            'nombre' => 'Sala renovada',
            'capacidad' => 120,
            'estado' => 1,
        ]);

        $this->actingAs($user)->get(route('salas.show', $room))->assertOk();
        $this->actingAs($user)->get(route('salas.edit', $room))->assertOk();
        $this->actingAs($user)->delete(route('salas.destroy', $room))
            ->assertRedirect(route('salas.index'));

        $this->assertDatabaseMissing('salas', ['id' => $room->id]);
    }

    public function test_room_with_functions_cannot_be_deleted(): void
    {
        $user = User::factory()->create();
        $function = Funciones::factory()->create();

        $this->actingAs($user)->delete(route('salas.destroy', $function->sala))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('salas', ['id' => $function->sala_id]);
    }
}
