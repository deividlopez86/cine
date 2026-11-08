<?php

namespace Database\Seeders;

use App\Models\Ciudades;
use App\Models\Departamentos;
use Illuminate\Database\Seeder;
use RuntimeException;

class CiudadesSeeder extends Seeder
{
    public function run(): void
    {
        $ciudades = [
            'Pasto' => 'Nariño',
            'Bogotá' => 'Cundinamarca',
            'Medellín' => 'Antioquia',
            'Cali' => 'Valle del Cauca',
        ];

        foreach ($ciudades as $nombre => $departamentoNombre) {
            $departamento = Departamentos::query()->where('nombre', $departamentoNombre)->first();

            if (! $departamento) {
                throw new RuntimeException("No existe el departamento {$departamentoNombre} requerido para crear {$nombre}.");
            }

            Ciudades::query()->firstOrCreate([
                'nombre' => $nombre,
                'id_departamento' => $departamento->id,
            ]);
        }
    }
}
