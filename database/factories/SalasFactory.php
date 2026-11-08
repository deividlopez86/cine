<?php

namespace Database\Factories;

use App\Models\Salas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Salas>
 */
class SalasFactory extends Factory
{
    protected $model = Salas::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->numerify('Sala ###'),
            'capacidad' => fake()->numberBetween(40, 250),
            'estado' => true,
        ];
    }
}
