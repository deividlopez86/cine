<?php

namespace App\Http\Controllers;

use App\Models\Ciudades;
use App\Models\Departamentos;
use App\Models\Funciones;
use App\Models\Peliculas;
use App\Models\Salas;
use App\Models\Sucursales;
use App\Models\Trabajadores;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GlobalSearchController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $validated = $request->validate([
            'search' => ['required', 'string', 'max:150'],
        ]);
        $search = trim($validated['search']);
        $pattern = '%'.$search.'%';

        $results = collect()
            ->concat(Peliculas::query()
                ->where('titulo', 'like', $pattern)
                ->limit(5)
                ->get(['id', 'titulo', 'genero', 'anio'])
                ->map(fn (Peliculas $movie) => [
                    'type' => 'Película',
                    'title' => $movie->titulo,
                    'detail' => implode(' · ', array_filter([$movie->genero, $movie->anio])),
                    'href' => "/peliculas/{$movie->id}",
                ]))
            ->concat(Funciones::query()
                ->with(['pelicula:id,titulo', 'sala:id,nombre'])
                ->whereHas('pelicula', fn ($query) => $query->where('titulo', 'like', $pattern))
                ->orWhere('fecha', 'like', $pattern)
                ->limit(5)
                ->get()
                ->map(fn (Funciones $showtime) => [
                    'type' => 'Función',
                    'title' => $showtime->pelicula?->titulo ?? 'Función programada',
                    'detail' => implode(' · ', array_filter([$showtime->fecha, $showtime->sala?->nombre])),
                    'href' => '/funciones',
                ]))
            ->concat(Salas::query()
                ->where('nombre', 'like', $pattern)
                ->limit(5)
                ->get(['id', 'nombre', 'capacidad'])
                ->map(fn (Salas $room) => [
                    'type' => 'Sala',
                    'title' => $room->nombre,
                    'detail' => $room->capacidad.' asientos',
                    'href' => '/salas',
                ]))
            ->concat(Departamentos::query()
                ->where('nombre', 'like', $pattern)
                ->limit(5)
                ->get(['id', 'nombre'])
                ->map(fn (Departamentos $department) => [
                    'type' => 'Departamento',
                    'title' => $department->nombre,
                    'detail' => 'Ubicación',
                    'href' => "/departamentos/{$department->id}",
                ]))
            ->concat(Ciudades::query()
                ->where('nombre', 'like', $pattern)
                ->limit(5)
                ->get(['id', 'nombre'])
                ->map(fn (Ciudades $city) => [
                    'type' => 'Ciudad',
                    'title' => $city->nombre,
                    'detail' => 'Ubicación',
                    'href' => "/ciudades/{$city->id}",
                ]))
            ->concat(Sucursales::query()
                ->where('nombre', 'like', $pattern)
                ->limit(5)
                ->get(['id', 'nombre', 'direccion'])
                ->map(fn (Sucursales $branch) => [
                    'type' => 'Sucursal',
                    'title' => $branch->nombre,
                    'detail' => $branch->direccion ?? 'Sede de CINE',
                    'href' => "/sucursales/{$branch->id}",
                ]))
            ->concat(Trabajadores::query()
                ->where(fn ($query) => $query
                    ->where('nombre', 'like', $pattern)
                    ->orWhere('apellido', 'like', $pattern))
                ->limit(5)
                ->get(['id', 'nombre', 'apellido', 'cargo'])
                ->map(fn (Trabajadores $worker) => [
                    'type' => 'Trabajador',
                    'title' => trim($worker->nombre.' '.$worker->apellido),
                    'detail' => $worker->cargo ?? 'Equipo CINE',
                    'href' => "/trabajadores/{$worker->id}",
                ]))
            ->values();

        return Inertia::render('Search/Index', [
            'title' => 'Búsqueda',
            'search' => $search,
            'results' => $results,
        ]);
    }
}
