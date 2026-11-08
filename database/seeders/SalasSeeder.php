<?php

namespace Database\Seeders;

use App\Models\Salas;
use Illuminate\Database\Seeder;

class SalasSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 5) as $number) {
            Salas::query()->firstOrCreate(
                ['nombre' => "Sala {$number}"],
                [
                    'capacidad' => fake()->numberBetween(40, 250),
                    'estado' => true,
                ],
            );
        }
    }
}
