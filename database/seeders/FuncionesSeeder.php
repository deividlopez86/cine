<?php

namespace Database\Seeders;

use App\Models\Funciones;
use App\Models\Peliculas;
use App\Models\Salas;
use Illuminate\Database\Seeder;
use RuntimeException;

class FuncionesSeeder extends Seeder
{
    public function run(): void
    {
        $salas = Salas::query()->inRandomOrder()->limit(5)->get();
        $peliculas = Peliculas::query()->inRandomOrder()->limit(5)->get();

        if ($salas->count() < 5 || $peliculas->count() < 5) {
            throw new RuntimeException('Se necesitan al menos 5 salas y 5 películas para crear las funciones.');
        }

        foreach ($salas as $index => $sala) {
            Funciones::factory()->create([
                'sala_id' => $sala->id,
                'pelicula_id' => $peliculas[$index]->id,
            ]);
        }
    }
}
