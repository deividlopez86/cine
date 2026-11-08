<?php

namespace Database\Seeders;

use App\Models\Sucursales;
use App\Models\Trabajadores;
use Illuminate\Database\Seeder;
use RuntimeException;

class TrabajadoresSeeder extends Seeder
{
    public function run(): void
    {
        $sucursales = Sucursales::query()->orderBy('id')->get();

        if ($sucursales->isEmpty()) {
            throw new RuntimeException('Se requiere al menos una sucursal para crear trabajadores de prueba.');
        }

        foreach ($sucursales as $index => $sucursal) {
            $email = 'trabajador' . ($index + 1) . '@example.test';

            Trabajadores::query()->firstOrCreate(
                ['email' => $email],
                [
                    'nombre' => ['Andrea', 'Camilo', 'Valentina', 'Santiago'][$index % 4],
                    'apellido' => ['Prueba', 'Ejemplo', 'Ficticio', 'Demo'][$index % 4],
                    'documento' => 'TEST-CO-' . str_pad((string) ($index + 1), 6, '0', STR_PAD_LEFT),
                    'telefono' => '+57 000 000 ' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    'direccion' => 'Dirección ficticia de prueba',
                    'cargo' => ['Taquilla', 'Operaciones', 'Servicio al cliente', 'Proyección'][$index % 4],
                    'id_sucursal' => $sucursal->id,
                ],
            );
        }
    }
}
