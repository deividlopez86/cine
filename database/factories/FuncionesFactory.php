<?php

namespace Database\Factories;

use App\Models\Funciones;
use App\Models\Peliculas;
use App\Models\Salas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Funciones>
 */
class FuncionesFactory extends Factory
{
    protected $model = Funciones::class;

    public function definition(): array
    {
        $horaInicio = fake()->numberBetween(10, 20);
        $duracion = fake()->numberBetween(90, 180);

        return [
            'pelicula_id' => Peliculas::factory(),
            'sala_id' => Salas::factory(),
            'fecha' => fake()->dateTimeBetween('today', '+30 days')->format('Y-m-d'),
            'hora_inicio' => sprintf('%02d:00:00', $horaInicio),
            'hora_fin' => sprintf('%02d:%02d:00', $horaInicio + intdiv($duracion, 60), $duracion % 60),
            'precio' => fake()->randomFloat(2, 5000, 15000),
            'estado' => true,
        ];
    }
}
