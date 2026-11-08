<?php

namespace App\Http\Controllers;

use App\Models\Peliculas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PeliculasController extends Controller
{
    public function index()
    {
        $filters = request()->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'genre' => ['nullable', 'string', 'max:50'],
            'year' => ['nullable', 'integer', 'between:1888,2100'],
        ]);
        $search = trim($filters['search'] ?? '');
        $genre = $filters['genre'] ?? '';
        $year = $filters['year'] ?? '';

        $peliculas = Peliculas::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('titulo', 'like', '%'.$search.'%');
            })
            ->when($genre !== '', function ($query) use ($genre) {
                $query->where('genero', $genre);
            })
            ->when($year !== '', function ($query) use ($year) {
                $query->where('anio', $year);
            })
            ->orderByDesc('anio')
            ->orderBy('titulo')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Peliculas/Index', [
            'peliculas' => $peliculas,
            'generos' => Peliculas::query()
                ->whereNotNull('genero')
                ->distinct()
                ->orderBy('genero')
                ->pluck('genero'),
            'filters' => [
                'search' => $search,
                'genre' => $genre,
                'year' => (string) $year,
            ],
            'years' => Peliculas::query()
                ->whereNotNull('anio')
                ->distinct()
                ->orderByDesc('anio')
                ->pluck('anio'),
        ]);
    }
    public function PeliculasDisponibles()
    {
        $peliculas = Peliculas::where('estado', 'activo')
            
            ->orderBy('titulo', 'asc')
            ->get();
            return $peliculas; 
 
    }

    public function create()
    {
        return Inertia::render('Peliculas/Create', [
            'generos' => Peliculas::GENEROS,
            'clasificaciones' => array_keys(Peliculas::CLASIFICACIONES),
        ]);
    }

    public function store(Request $request)
    {
        $attributes = $request->validate($this->rules());
        $attributes['slug'] = $this->uniqueSlug($attributes['titulo']);

        Peliculas::create($attributes);

        return redirect()->route('peliculas.index')->with('success', 'Película agregada correctamente.');
    }

    public function show(Peliculas $pelicula)
    {
        return Inertia::render('Peliculas/Show', ['pelicula' => $pelicula]);
    }

    public function edit(Peliculas $pelicula)
    {
        return Inertia::render('Peliculas/Edit', [
            'pelicula' => $pelicula,
            'generos' => Peliculas::GENEROS,
            'clasificaciones' => array_keys(Peliculas::CLASIFICACIONES),
        ]);
    }

    public function update(Request $request, Peliculas $pelicula)
    {
        $attributes = $request->validate($this->rules());
        $attributes['slug'] = $this->uniqueSlug($attributes['titulo'], $pelicula->id);
        $pelicula->update($attributes);

        return redirect()->route('peliculas.index')->with('success', 'Cambios guardados correctamente.');
    }

    
   
    public function destroy(Peliculas $pelicula)
    {
        $pelicula->delete();

        return redirect()->route('peliculas.index')->with('success', 'Película eliminada correctamente.');
    }

    private function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:150'],
            'director' => ['nullable', 'string', 'max:120'],
            'anio' => ['nullable', 'integer', 'between:1888,2100'],
            'genero' => ['required', 'string', 'max:50'],
            'duracion_minutos' => ['required', 'integer', 'between:1,1000'],
            'clasificacion' => ['required', 'string', 'max:10'],
            'sinopsis' => ['required', 'string', 'max:5000'],
            'imagen' => ['nullable', 'url', 'max:2048'],
        ];
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::limit(Str::slug($title) ?: 'pelicula', 170, '');
        $slug = $base;
        $suffix = 2;

        while (Peliculas::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()) {
            $slug = Str::limit($base, 170 - strlen((string) $suffix), '').'-'.$suffix++;
        }

        return $slug;
    }
     

}
