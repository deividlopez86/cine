<?php

namespace Database\Factories;

use App\Models\Peliculas;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Peliculas>
 */
class PeliculasFactory extends Factory
{
    protected $model = Peliculas::class;

    public function definition(): array
    {
        $titulo = fake()->unique()->sentence(3);

        return [
            'titulo' => $titulo,
            'slug' => Str::slug($titulo),
            'sinopsis' => fake()->paragraphs(3, true),
            'imagen' => null,
            'duracion_minutos' => fake()->numberBetween(80, 180),
            'genero' => fake()->randomElement(Peliculas::GENEROS),
            'clasificacion' => fake()->randomElement(array_keys(Peliculas::CLASIFICACIONES)),
            'director' => fake()->name(),
            'anio' => fake()->numberBetween(1950, (int) date('Y')),
            'fecha_estreno' => fake()->date(),
            'estado' => 'activo',
        ];
    }
}
