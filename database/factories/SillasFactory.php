<?php

namespace Database\Factories;

use App\Models\Sillas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sillas>
 */
class SillasFactory extends Factory
{
    protected $model = Sillas::class;

    public function definition(): array
    {
        return [
            'sala_id' => \App\Models\Salas::factory(),
            'fila' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E']),
            'numero' => $this->faker->numberBetween(1, 20),
            'codigo' => $this->faker->regexify('[A-E][1-9]|1[0-9]|20'),
        ];
    }
}
