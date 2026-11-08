<?php

namespace Database\Factories;

use App\Models\Ciudades;
use App\Models\Departamentos;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ciudades>
 */
class CiudadesFactory extends Factory
{
    protected $model = Ciudades::class;

    public function definition(): array
    {
        $ciudadesPorDepartamento = [
            'Nariño' => 'Pasto',
            'Cundinamarca' => 'Bogotá',
            'Antioquia' => 'Medellín',
            'Valle del Cauca' => 'Cali',
        ];
        $departamento = Departamentos::factory()->create();

        return [
            'nombre' => $ciudadesPorDepartamento[$departamento->nombre] ?? fake()->city(),
            'descripcion' => fake()->sentence(),
            'id_departamento' => $departamento,
        ];
    }
}
