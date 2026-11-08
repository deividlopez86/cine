<?php

namespace App\Http\Controllers;

use App\Models\Salas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class SalasController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim((string) $request->query('search', ''));
        $estado = $request->query('estado', '');

        $records = Salas::query()
            ->withCount('funciones')
            ->when($search, fn ($query) => $query->where('nombre', 'like', "%{$search}%"))
            ->when($estado !== '', fn ($query) => $query->where('estado', $estado === 'activo'))
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString()
            ->through(fn (Salas $sala) => array_merge($sala->toArray(), [
                'estado' => $sala->estado ? 'activo' : 'inactivo',
            ]));

        return Inertia::render('Catalogos/Index', [
            'title' => 'Salas',
            'description' => 'Administra la capacidad y disponibilidad de las salas.',
            'resource' => 'salas',
            'createUrl' => route('salas.create'),
            'records' => $records,
            'columns' => [
                ['key' => 'id', 'label' => 'ID'],
                ['key' => 'nombre', 'label' => 'Sala'],
                ['key' => 'capacidad', 'label' => 'Capacidad'],
                ['key' => 'estado', 'label' => 'Estado'],
                ['key' => 'funciones_count', 'label' => 'Funciones'],
            ],
            'search' => $search,
            'filters' => ['estado' => $estado],
        ]);
    }

    public function create(): Response
    {
        return $this->form();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:80', Rule::unique('salas', 'nombre')],
            'capacidad' => ['required', 'integer', 'between:1,65535'],
            'estado' => ['required', 'boolean'],
        ]);

        Salas::query()->create($validated);

        return to_route('salas.index')->with('success', 'Sala creada correctamente.');
    }

    public function show(Salas $sala): Response
    {
        return Inertia::render('Catalogos/Show', [
            'title' => 'Detalle de la sala',
            'resource' => 'salas',
            'editUrl' => route('salas.edit', $sala),
            'records' => [
                ['label' => 'ID', 'value' => $sala->id],
                ['label' => 'Nombre', 'value' => $sala->nombre],
                ['label' => 'Capacidad', 'value' => $sala->capacidad],
                ['label' => 'Estado', 'value' => $sala->estado ? 'Disponible' : 'Fuera de servicio'],
                ['label' => 'Funciones programadas', 'value' => $sala->funciones()->count()],
            ],
        ]);
    }

    public function edit(Salas $sala): Response
    {
        return $this->form($sala);
    }

    public function update(Request $request, Salas $sala)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:80', Rule::unique('salas', 'nombre')->ignore($sala->id)],
            'capacidad' => ['required', 'integer', 'between:1,65535'],
            'estado' => ['required', 'boolean'],
        ]);

        $sala->update($validated);

        return to_route('salas.index')->with('success', 'Sala actualizada correctamente.');
    }

    public function destroy(Salas $sala)
    {
        if ($sala->funciones()->exists()) {
            return back()->with('error', 'No se puede eliminar la sala porque tiene funciones asociadas.');
        }

        $sala->delete();

        return to_route('salas.index')->with('success', 'Sala eliminada correctamente.');
    }

    private function form(?Salas $sala = null): Response
    {
        $editing = $sala !== null;

        return Inertia::render('Catalogos/Form', [
            'title' => $editing ? 'Editar sala' : 'Crear sala',
            'resource' => 'salas',
            'action' => $editing ? route('salas.update', $sala) : route('salas.store'),
            'method' => $editing ? 'put' : 'post',
            'cancelUrl' => route('salas.index'),
            'fields' => [
                ['name' => 'nombre', 'label' => 'Nombre de la sala', 'required' => true],
                ['name' => 'capacidad', 'label' => 'Capacidad', 'type' => 'number', 'min' => 1, 'max' => 65535, 'step' => 1, 'required' => true],
                ['name' => 'estado', 'label' => 'Disponibilidad', 'type' => 'select', 'required' => true,
                    'options' => [
                        ['value' => 1, 'label' => 'Disponible'],
                        ['value' => 0, 'label' => 'Fuera de servicio'],
                    ]],
            ],
            'values' => $editing
                ? [...$sala->only(['nombre', 'capacidad']), 'estado' => $sala->estado ? 1 : 0]
                : ['nombre' => '', 'capacidad' => '', 'estado' => 1],
        ]);
    }
}
