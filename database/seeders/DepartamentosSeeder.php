<?php

namespace Database\Seeders;

use App\Models\Departamentos;
use Illuminate\Database\Seeder;

class DepartamentosSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Nariño', 'Cundinamarca', 'Antioquia', 'Valle del Cauca'] as $nombre) {
            Departamentos::query()->firstOrCreate(['nombre' => $nombre]);
        }
    }
}
