<?php

namespace Database\Factories;

use App\Models\Departamentos;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Departamentos>
 */
class DepartamentosFactory extends Factory
{
    protected $model = Departamentos::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->randomElement([
                'Nariño',
                'Cundinamarca',
                'Antioquia',
                'Valle del Cauca',
                'Santander',
                'Atlántico',
            ]),
            'descripcion' => fake()->sentence(),
        ];
    }
}
