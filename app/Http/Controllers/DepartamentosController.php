<?php

namespace App\Http\Controllers;

use App\Models\Departamentos;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class DepartamentosController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim((string) $request->query('search', ''));

        return Inertia::render('Catalogos/Index', [
            'title' => 'Departamentos',
            'description' => 'Administra los departamentos donde opera CINE.',
            'resource' => 'departamentos',
            'createUrl' => route('departamentos.create'),
            'records' => Departamentos::query()
                ->withCount('ciudades')
                ->when($search, fn ($query) => $query->where('nombre', 'like', "%{$search}%"))
                ->orderBy('nombre')
                ->paginate(10)
                ->withQueryString(),
            'columns' => [
                ['key' => 'id', 'label' => 'ID'],
                ['key' => 'nombre', 'label' => 'Departamento'],
                ['key' => 'ciudades_count', 'label' => 'Ciudades'],
            ],
            'search' => $search,
            'filters' => [],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Catalogos/Form', [
            'title' => 'Crear departamento',
            'resource' => 'departamentos',
            'action' => route('departamentos.store'),
            'method' => 'post',
            'cancelUrl' => route('departamentos.index'),
            'fields' => [
                ['name' => 'nombre', 'label' => 'Nombre', 'required' => true],
                ['name' => 'descripcion', 'label' => 'Descripción', 'type' => 'textarea'],
            ],
            'values' => ['nombre' => '', 'descripcion' => ''],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100', Rule::unique('departamentos', 'nombre')],
            'descripcion' => ['nullable', 'string', 'max:2000'],
        ]);

        Departamentos::query()->create($validated);

        return to_route('departamentos.index')->with('success', 'Departamento creado correctamente.');
    }

    public function edit(Departamentos $departamento): Response
    {
        return Inertia::render('Catalogos/Form', [
            'title' => 'Editar departamento',
            'resource' => 'departamentos',
            'action' => route('departamentos.update', $departamento),
            'method' => 'put',
            'cancelUrl' => route('departamentos.index'),
            'fields' => [
                ['name' => 'nombre', 'label' => 'Nombre', 'required' => true],
                ['name' => 'descripcion', 'label' => 'Descripción', 'type' => 'textarea'],
            ],
            'values' => $departamento->only(['nombre', 'descripcion']),
        ]);
    }

    public function update(Request $request, Departamentos $departamento)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100', Rule::unique('departamentos', 'nombre')->ignore($departamento->id)],
            'descripcion' => ['nullable', 'string', 'max:2000'],
        ]);

        $departamento->update($validated);

        return to_route('departamentos.index')->with('success', 'Departamento actualizado correctamente.');
    }

    public function destroy(Departamentos $departamento)
    {
        if ($departamento->ciudades()->exists()) {
            return back()->with('error', 'No se puede eliminar el departamento porque tiene ciudades asociadas.');
        }

        $departamento->delete();

        return to_route('departamentos.index')->with('success', 'Departamento eliminado correctamente.');
    }

    public function show(Departamentos $departamento): \Inertia\Response
    {
        return Inertia::render('Catalogos/Show', [
            'title' => 'Detalle del departamento',
            'resource' => 'departamentos',
            'editUrl' => route('departamentos.edit', $departamento),
            'records' => [
                ['label' => 'ID', 'value' => $departamento->id],
                ['label' => 'Nombre', 'value' => $departamento->nombre],
                ['label' => 'Descripción', 'value' => $departamento->descripcion],
                ['label' => 'Ciudades relacionadas', 'value' => $departamento->ciudades()->count()],
            ],
        ]);
    }
}
