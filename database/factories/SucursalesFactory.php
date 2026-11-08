<?php

namespace Database\Factories;

use App\Models\Ciudades;
use App\Models\Sucursales;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sucursales>
 */
class SucursalesFactory extends Factory
{
    protected $model = Sucursales::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->randomElement(['Cine Centro', 'Cine Norte', 'Cine Plaza', 'Cine Andino']),
            'descripcion' => fake()->sentence(),
            'id_ciudad' => Ciudades::factory(),
            'direccion' => 'Dirección ficticia ' . fake()->numerify('###'),
            'telefono' => '+57 000 000 ' . fake()->numerify('####'),
        ];
    }
}
