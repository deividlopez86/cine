<?php

namespace Database\Factories;

use App\Models\Sucursales;
use App\Models\Trabajadores;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Trabajadores>
 */
class TrabajadoresFactory extends Factory
{
    protected $model = Trabajadores::class;

    public function definition(): array
    {
        $nombres = fake()->firstName();
        $apellidos = fake()->lastName();

        return [
            'nombre' => $nombres,
            'apellido' => $apellidos,
            'documento' => 'TEST-CO-' . fake()->unique()->numerify('######'),
            'email' => fake()->unique()->userName() . '@example.test',
            'telefono' => '+57 000 000 ' . fake()->numerify('####'),
            'direccion' => 'Dirección ficticia ' . fake()->numerify('###'),
            'cargo' => fake()->randomElement(['Taquilla', 'Operaciones', 'Servicio al cliente', 'Proyección']),
            'id_sucursal' => Sucursales::factory(),
        ];
    }
}
