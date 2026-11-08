<?php

namespace Tests\Feature;

use App\Models\Salas;
use Database\Seeders\SalasSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalasSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeding_rooms_multiple_times_does_not_create_duplicates(): void
    {
        $this->seed(SalasSeeder::class);
        $this->seed(SalasSeeder::class);

        $this->assertDatabaseCount('salas', 5);
        $this->assertSame(5, Salas::query()->distinct('nombre')->count('nombre'));
    }
}
