<?php

namespace Tests\Feature;

use App\Models\Ciudades;
use App\Models\Departamentos;
use App\Models\Sucursales;
use App\Models\Trabajadores;
use App\Models\User;
use Database\Seeders\CiudadesSeeder;
use Database\Seeders\DepartamentosSeeder;
use Database\Seeders\SucursalesSeeder;
use Database\Seeders\TrabajadoresSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CineCatalogosTest extends TestCase
{
    use RefreshDatabase;

    public function test_factories_create_a_valid_catalog_relationship_chain(): void
    {
        $trabajador = Trabajadores::factory()->create();
        $sucursal = $trabajador->sucursal;
        $ciudad = $sucursal->ciudad;
        $departamento = $ciudad->departamento;

        $this->assertInstanceOf(Sucursales::class, $sucursal);
        $this->assertInstanceOf(Ciudades::class, $ciudad);
        $this->assertInstanceOf(Departamentos::class, $departamento);
        $this->assertSame($departamento->id, $ciudad->id_departamento);
        $this->assertSame($ciudad->id, $sucursal->id_ciudad);
        $this->assertSame($sucursal->id, $trabajador->id_sucursal);
        $this->assertStringEndsWith('@example.test', $trabajador->email);
        $this->assertStringStartsWith('TEST-CO-', $trabajador->documento);
    }

    public function test_catalog_crud_routes_use_related_database_options(): void
    {
        $this->actingAs(User::factory()->create());

        $departamento = Departamentos::factory()->create(['nombre' => 'Nariño']);
        $this->get('/ciudades/create')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Catalogos/Form')
                ->where('title', 'Crear ciudad')
                ->has('fields.1.options', 1));

        $this->post('/ciudades', [
            'nombre' => 'Pasto',
            'id_departamento' => $departamento->id,
        ])->assertRedirect('/ciudades');

        $ciudad = Ciudades::query()->where('nombre', 'Pasto')->firstOrFail();
        $this->put("/ciudades/{$ciudad->id}", [
            'nombre' => 'Pasto Centro',
            'id_departamento' => $departamento->id,
        ])->assertRedirect('/ciudades');

        $this->post('/sucursales', [
            'nombre' => 'Cine Centro Prueba',
            'id_ciudad' => $ciudad->id,
            'direccion' => 'Dirección ficticia de prueba',
            'telefono' => '+57 000 000 0000',
            'estado' => 'activo',
        ])->assertRedirect('/sucursales');

        $sucursal = Sucursales::query()->where('nombre', 'Cine Centro Prueba')->firstOrFail();
        $this->post('/trabajadores', [
            'nombre' => 'Andrea',
            'apellido' => 'Prueba',
            'tipo_documento' => 'CC',
            'documento' => 'TEST-CO-990001',
            'telefono' => '+57 000 000 0000',
            'direccion' => 'Dirección ficticia de prueba',
            'email' => 'andrea@example.test',
            'cargo' => 'Taquilla',
            'fecha_contratacion' => '2026-01-15',
            'estado' => 'activo',
            'id_sucursal' => $sucursal->id,
        ])->assertRedirect('/trabajadores');

        $this->assertDatabaseHas('trabajadores', [
            'email' => 'andrea@example.test',
            'id_sucursal' => $sucursal->id,
        ]);

        $trabajador = Trabajadores::query()->where('email', 'andrea@example.test')->firstOrFail();
        $this->get("/trabajadores/{$trabajador->id}")
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Catalogos/Show')
                ->where('title', 'Detalle del trabajador'));
    }

    public function test_related_catalog_records_cannot_be_deleted(): void
    {
        $this->actingAs(User::factory()->create());

        $trabajador = Trabajadores::factory()->create();
        $sucursal = $trabajador->sucursal;
        $ciudad = $sucursal->ciudad;
        $departamento = $ciudad->departamento;

        $this->delete("/departamentos/{$departamento->id}")
            ->assertSessionHas('error');
        $this->delete("/ciudades/{$ciudad->id}")
            ->assertSessionHas('error');
        $this->delete("/sucursales/{$sucursal->id}")
            ->assertSessionHas('error');

        $this->assertDatabaseHas('departamentos', ['id' => $departamento->id]);
        $this->assertDatabaseHas('ciudades', ['id' => $ciudad->id]);
        $this->assertDatabaseHas('sucursales', ['id' => $sucursal->id]);
    }

    public function test_catalog_seeders_are_ordered_and_safe_to_repeat(): void
    {
        $seeders = [
            DepartamentosSeeder::class,
            CiudadesSeeder::class,
            SucursalesSeeder::class,
            TrabajadoresSeeder::class,
        ];

        foreach ($seeders as $seeder) {
            $this->seed($seeder);
        }

        $this->assertDatabaseCount('departamentos', 4);
        $this->assertDatabaseCount('ciudades', 4);
        $this->assertDatabaseCount('sucursales', 4);
        $this->assertDatabaseCount('trabajadores', 4);

        foreach ($seeders as $seeder) {
            $this->seed($seeder);
        }

        $this->assertDatabaseCount('departamentos', 4);
        $this->assertDatabaseCount('ciudades', 4);
        $this->assertDatabaseCount('sucursales', 4);
        $this->assertDatabaseCount('trabajadores', 4);
    }
}
