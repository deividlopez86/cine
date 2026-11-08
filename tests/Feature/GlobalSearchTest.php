<?php

namespace Tests\Feature;

use App\Models\Peliculas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class GlobalSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_can_search_across_cine_records(): void
    {
        Peliculas::factory()->create(['titulo' => 'Noche de estreno']);
        $this->actingAs(User::factory()->create());

        $this->get('/buscar?search=estreno')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Search/Index')
                ->where('search', 'estreno')
                ->has('results', 1)
                ->where('results.0.type', 'Película')
                ->where('results.0.title', 'Noche de estreno')
                ->where('results.0.href', '/peliculas/1'));
    }

    public function test_global_search_requires_authentication(): void
    {
        $this->get('/buscar?search=estreno')->assertRedirect('/login');
    }
}
