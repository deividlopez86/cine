<?php

namespace Tests\Feature;

use App\Models\Funciones;
use App\Models\Salas;
use App\Models\Sillas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SillasDisponiblesTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_only_seats_from_the_function_room(): void
    {
        $room = Salas::factory()->create();
        $otherRoom = Salas::factory()->create();
        $function = Funciones::factory()->create(['sala_id' => $room->id]);
        $seat = Sillas::factory()->create(['sala_id' => $room->id]);
        Sillas::factory()->create(['sala_id' => $otherRoom->id]);

        $this->getJson("/sillasdisponibles/{$function->id}")
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $seat->id);
    }

    public function test_it_returns_not_found_for_a_missing_function(): void
    {
        $this->getJson('/sillasdisponibles/999')
            ->assertNotFound();
    }
}
