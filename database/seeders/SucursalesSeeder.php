<?php

namespace Database\Seeders;

use App\Models\Ciudades;
use App\Models\Sucursales;
use Illuminate\Database\Seeder;
use RuntimeException;

class SucursalesSeeder extends Seeder
{
    public function run(): void
    {
        $sucursales = [
            'Cine Centro Pasto' => 'Pasto',
            'Cine Norte Bogotá' => 'Bogotá',
            'Cine Plaza Medellín' => 'Medellín',
            'Cine Sur Cali' => 'Cali',
        ];

        foreach ($sucursales as $nombre => $ciudadNombre) {
            $ciudad = Ciudades::query()->where('nombre', $ciudadNombre)->first();

            if (! $ciudad) {
                throw new RuntimeException("No existe la ciudad {$ciudadNombre} requerida para crear {$nombre}.");
            }

            Sucursales::query()->firstOrCreate(
                ['nombre' => $nombre],
                [
                    'id_ciudad' => $ciudad->id,
                    'direccion' => 'Dirección ficticia ' . $nombre,
                    'telefono' => '+57 000 000 0000',
                ],
            );
        }
    }
}
