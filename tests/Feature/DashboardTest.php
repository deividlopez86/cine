<?php

namespace Tests\Feature;

use App\Models\Funciones;
use App\Models\Trabajadores;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_live_catalog_counts_to_verified_users(): void
    {
        Trabajadores::factory()->create();
        Funciones::factory()->create();
        $this->actingAs(User::factory()->create());

        $this->get('/dashboard')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Dashboard')
                ->where('metrics.peliculas', 1)
                ->where('metrics.funciones', 1)
                ->where('metrics.salas', 1)
                ->where('metrics.ciudades', 1)
                ->where('metrics.sucursales', 1)
                ->where('metrics.trabajadores', 1)
                ->where('metrics.departamentos', 1));
    }

    public function test_dashboard_requires_authentication(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }
}
