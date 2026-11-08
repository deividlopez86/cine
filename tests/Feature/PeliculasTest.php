<?php

namespace Tests\Feature;

use App\Models\Peliculas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PeliculasTest extends TestCase
{
    use RefreshDatabase;

    public function test_movie_catalog_renders_an_empty_state_without_movies(): void
    {
        $this->get('/peliculas')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Peliculas/Index')
                ->has('peliculas.data', 0)
                ->has('generos', 0)
                ->where('filters.search', '')
                ->where('filters.genre', '')
                ->where('filters.year', ''));
    }

    public function test_movie_catalog_filters_by_title_and_genre(): void
    {
        Peliculas::create([
            'titulo' => 'La gran aventura',
            'sinopsis' => 'Una historia para toda la familia.',
            'duracion_minutos' => 110,
            'genero' => 'Aventura',
            'clasificacion' => 'ATP',
        ]);
        Peliculas::create([
            'titulo' => 'Una comedia',
            'sinopsis' => 'Una historia divertida.',
            'duracion_minutos' => 95,
            'genero' => 'Comedia',
            'clasificacion' => '+12',
        ]);

        $this->get('/peliculas?search=aventura&genre=Aventura')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Peliculas/Index')
                ->has('peliculas.data', 1)
                ->where('peliculas.data.0.titulo', 'La gran aventura')
                ->where('filters.search', 'aventura')
                ->where('filters.genre', 'Aventura')
                ->has('generos', 2));
    }

    public function test_public_homepage_uses_movies_from_the_database(): void
    {
        Peliculas::create([
            'titulo' => 'Cine de prueba',
            'sinopsis' => 'Una historia.',
            'duracion_minutos' => 100,
            'genero' => 'Drama',
            'clasificacion' => 'ATP',
            'anio' => 2025,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Welcome')
                ->where('destacada.titulo', 'Cine de prueba')
                ->has('peliculas', 1));
    }

    public function test_movie_creation_requires_authentication_and_generates_unique_slug(): void
    {
        $this->get('/peliculas/create')->assertRedirect('/login');

        $this->actingAs(User::factory()->create())
            ->post('/peliculas', [
                'titulo' => 'Una película increíble',
                'director' => 'Ana Directora',
                'anio' => 2024,
                'genero' => 'Drama',
                'duracion_minutos' => 110,
                'clasificacion' => '+12',
                'sinopsis' => 'Una sinopsis válida.',
                'imagen' => '',
            ])
            ->assertRedirect(route('peliculas.index'));

        $this->assertDatabaseHas('peliculas', [
            'titulo' => 'Una película increíble',
            'slug' => 'una-pelicula-increible',
        ]);

        $this->actingAs(User::factory()->create())
            ->post('/peliculas', [
                'titulo' => 'Una película increíble',
                'genero' => 'Drama',
                'duracion_minutos' => 110,
                'clasificacion' => '+12',
                'sinopsis' => 'Otra sinopsis válida.',
            ])
            ->assertRedirect(route('peliculas.index'));

        $this->assertDatabaseHas('peliculas', ['slug' => 'una-pelicula-increible-2']);
    }

    public function test_movie_creation_validates_required_fields(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/peliculas', [])
            ->assertSessionHasErrors(['titulo', 'genero', 'duracion_minutos', 'clasificacion', 'sinopsis']);

        $this->assertDatabaseCount('peliculas', 0);
    }

    public function test_movie_update_changes_the_existing_record_and_show_is_public(): void
    {
        $movie = Peliculas::create([
            'titulo' => 'Título anterior',
            'sinopsis' => 'Sinopsis anterior.',
            'duracion_minutos' => 90,
            'genero' => 'Drama',
            'clasificacion' => 'ATP',
        ]);

        $this->get("/peliculas/{$movie->id}")
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Peliculas/Show')
                ->where('pelicula.titulo', 'Título anterior'));

        $this->actingAs(User::factory()->create())
            ->put("/peliculas/{$movie->id}", [
                'titulo' => 'Título actualizado',
                'sinopsis' => 'Sinopsis nueva.',
                'duracion_minutos' => 120,
                'genero' => 'Aventura',
                'clasificacion' => '+12',
                'anio' => 2023,
            ])
            ->assertRedirect(route('peliculas.index'));

        $this->assertDatabaseCount('peliculas', 1);
        $this->assertDatabaseHas('peliculas', [
            'id' => $movie->id,
            'titulo' => 'Título actualizado',
            'slug' => 'titulo-actualizado',
            'duracion_minutos' => 120,
        ]);
    }

    public function test_movie_deletion_requires_authentication_and_removes_the_selected_movie(): void
    {
        $movie = Peliculas::create([
            'titulo' => 'Para eliminar',
            'sinopsis' => 'Sinopsis.',
            'duracion_minutos' => 90,
            'genero' => 'Drama',
            'clasificacion' => 'ATP',
        ]);

        $this->delete("/peliculas/{$movie->id}")->assertRedirect('/login');

        $this->actingAs(User::factory()->create())
            ->delete("/peliculas/{$movie->id}")
            ->assertRedirect(route('peliculas.index'));

        $this->assertDatabaseMissing('peliculas', ['id' => $movie->id]);
    }
}
