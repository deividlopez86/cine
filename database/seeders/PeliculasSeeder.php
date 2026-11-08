<?php

namespace Database\Seeders;

use App\Models\Peliculas;
use Illuminate\Database\Seeder;

class PeliculasSeeder extends Seeder
{
    public function run(): void
    {
        Peliculas::factory()->count(70)->create();
    }
}
