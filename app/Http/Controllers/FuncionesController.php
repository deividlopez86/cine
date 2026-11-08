<?php

namespace App\Http\Controllers;

use App\Models\Funciones;
use App\Models\Peliculas;
use App\Models\Salas;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class FuncionesController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim((string) $request->query('search', ''));
        $estado = $request->query('estado', '');

        $records = Funciones::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($matches) use ($search) {
                    $peliculaIds = Peliculas::query()
                        ->where('titulo', 'like', "%{$search}%")
                        ->pluck('id');
                    $salaIds = Salas::query()
                        ->where('nombre', 'like', "%{$search}%")
                        ->pluck('id');

                    $matches->whereIn('pelicula_id', $peliculaIds)
                        ->orWhereIn('sala_id', $salaIds);
                });
            })
            ->when($estado !== '', fn ($query) => $query->where('estado', $estado === 'activo'))
            ->orderByDesc('fecha')
            ->orderBy('hora_inicio')
            ->paginate(10)
            ->withQueryString()
            ->through(function (Funciones $funcion) {
                $pelicula = Peliculas::query()->find($funcion->pelicula_id);
                $sala = Salas::query()->find($funcion->sala_id);

                return array_merge($funcion->toArray(), [
                    'pelicula' => $pelicula ? ['id' => $pelicula->id, 'titulo' => $pelicula->titulo] : null,
                    'sala' => $sala ? ['id' => $sala->id, 'nombre' => $sala->nombre] : null,
                    'estado' => $funcion->estado ? 'activo' : 'inactivo',
                    'hora_inicio' => substr((string) $funcion->hora_inicio, 0, 5),
                    'hora_fin' => substr((string) $funcion->hora_fin, 0, 5),
                ]);
            });

        return Inertia::render('Catalogos/Index', [
            'title' => 'Funciones',
            'description' => 'Programa películas por sala, fecha y horario.',
            'resource' => 'funciones',
            'createUrl' => route('funciones.create'),
            'records' => $records,
            'columns' => [
                ['key' => 'pelicula.titulo', 'label' => 'Película'],
                ['key' => 'sala.nombre', 'label' => 'Sala'],
                ['key' => 'fecha', 'label' => 'Fecha'],
                ['key' => 'hora_inicio', 'label' => 'Inicio'],
                ['key' => 'hora_fin', 'label' => 'Fin'],
                ['key' => 'precio', 'label' => 'Precio'],
                ['key' => 'estado', 'label' => 'Estado'],
            ],
            'search' => $search,
            'filters' => ['estado' => $estado],
        ]);
    }

    public function create(): Response
    {
        return $this->form();
    }

    public function FuncionesDisponibles($id_pelicula)
    {
        $funciones = Funciones::query()
            ->where('pelicula_id', $id_pelicula)
            ->where('estado', 1)
            ->get()
            ->map(function (Funciones $funcion) {
                $pelicula = Peliculas::query()->find($funcion->pelicula_id);
                $sala = Salas::query()->find($funcion->sala_id);

                return array_merge($funcion->toArray(), [
                    'pelicula' => $pelicula ? ['id' => $pelicula->id, 'titulo' => $pelicula->titulo] : null,
                    'sala' => $sala ? ['id' => $sala->id, 'nombre' => $sala->nombre] : null,
                ]);
            });

        return response()->json($funciones);
    }

    public function store(Request $request)
    {
        $validated = $this->validateFunction($request);
        $this->ensureNoScheduleConflict($validated);

        Funciones::query()->create($validated);

        return to_route('funciones.index')->with('success', 'Función programada correctamente.');
    }

    public function show(Funciones $funcion): Response
    {
        $pelicula = Peliculas::query()->find($funcion->pelicula_id);
        $sala = Salas::query()->find($funcion->sala_id);

        return Inertia::render('Catalogos/Show', [
            'title' => 'Detalle de la función',
            'resource' => 'funciones',
            'editUrl' => route('funciones.edit', $funcion),
            'records' => [
                ['label' => 'Película', 'value' => $pelicula ? $pelicula->titulo : null],
                ['label' => 'Sala', 'value' => $sala ? $sala->nombre : null],
                ['label' => 'Fecha', 'value' => $funcion->fecha ? $funcion->fecha->format('Y-m-d') : null],
                ['label' => 'Hora de inicio', 'value' => substr((string) $funcion->hora_inicio, 0, 5)],
                ['label' => 'Hora de fin', 'value' => substr((string) $funcion->hora_fin, 0, 5)],
                ['label' => 'Precio', 'value' => $funcion->precio],
                ['label' => 'Estado', 'value' => $funcion->estado ? 'Activa' : 'Inactiva'],
            ],
        ]);
    }

    public function edit(Funciones $funcion): Response
    {
        return $this->form($funcion);
    }

    public function update(Request $request, Funciones $funcion)
    {
        $validated = $this->validateFunction($request);
        $this->ensureNoScheduleConflict($validated, $funcion);
        $funcion->update($validated);

        return to_route('funciones.index')->with('success', 'Función actualizada correctamente.');
    }

    public function destroy(Funciones $funcion)
    {
        $funcion->delete();

        return to_route('funciones.index')->with('success', 'Función eliminada correctamente.');
    }

    private function validateFunction(Request $request): array
    {
        return $request->validate([
            'pelicula_id' => ['required', 'integer', 'exists:peliculas,id'],
            'sala_id' => ['required', 'integer', 'exists:salas,id'],
            'fecha' => ['required', 'date'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'precio' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'estado' => ['required', 'boolean'],
        ]);
    }

    private function ensureNoScheduleConflict(array $attributes, ?Funciones $ignore = null): void
    {
        if (! $attributes['estado']) {
            return;
        }

        $overlap = Funciones::query()
            ->where('sala_id', $attributes['sala_id'])
            ->whereDate('fecha', $attributes['fecha'])
            ->where('estado', true)
            ->where('hora_inicio', '<', $attributes['hora_fin'])
            ->where('hora_fin', '>', $attributes['hora_inicio'])
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->id))
            ->exists();

        if ($overlap) {
            throw ValidationException::withMessages([
                'hora_inicio' => 'La sala ya tiene una función activa en ese horario.',
            ]);
        }
    }

    private function form(?Funciones $funcion = null): Response
    {
        $editing = $funcion !== null;

        return Inertia::render('Catalogos/Form', [
            'title' => $editing ? 'Editar función' : 'Programar función',
            'resource' => 'funciones',
            'action' => $editing ? route('funciones.update', $funcion) : route('funciones.store'),
            'method' => $editing ? 'put' : 'post',
            'cancelUrl' => route('funciones.index'),
            'fields' => [
                ['name' => 'pelicula_id', 'label' => 'Película', 'type' => 'select', 'required' => true,
                    'options' => Peliculas::query()->orderBy('titulo')->get(['id', 'titulo'])
                        ->map(fn (Peliculas $movie) => ['value' => $movie->id, 'label' => $movie->titulo])],
                ['name' => 'sala_id', 'label' => 'Sala', 'type' => 'select', 'required' => true,
                    'options' => Salas::query()->orderBy('nombre')->get(['id', 'nombre'])
                        ->map(fn (Salas $room) => ['value' => $room->id, 'label' => $room->nombre])],
                ['name' => 'fecha', 'label' => 'Fecha', 'type' => 'date', 'required' => true],
                ['name' => 'hora_inicio', 'label' => 'Hora de inicio', 'type' => 'time', 'required' => true],
                ['name' => 'hora_fin', 'label' => 'Hora de fin', 'type' => 'time', 'required' => true],
                ['name' => 'precio', 'label' => 'Precio', 'type' => 'number', 'min' => 0, 'max' => 999999.99, 'step' => '0.01', 'required' => true],
                ['name' => 'estado', 'label' => 'Estado', 'type' => 'select', 'required' => true,
                    'options' => [
                        ['value' => 1, 'label' => 'Activa'],
                        ['value' => 0, 'label' => 'Inactiva'],
                    ]],
            ],
            'values' => $editing
                ? [
                    'pelicula_id' => $funcion->pelicula_id,
                    'sala_id' => $funcion->sala_id,
                    'fecha' => $funcion->fecha ? $funcion->fecha->format('Y-m-d') : null,
                    'hora_inicio' => substr((string) $funcion->hora_inicio, 0, 5),
                    'hora_fin' => substr((string) $funcion->hora_fin, 0, 5),
                    'precio' => $funcion->precio,
                    'estado' => $funcion->estado ? 1 : 0,
                ]
                : ['pelicula_id' => '', 'sala_id' => '', 'fecha' => '', 'hora_inicio' => '', 'hora_fin' => '', 'precio' => '', 'estado' => 1],
        ]);
    }
}
