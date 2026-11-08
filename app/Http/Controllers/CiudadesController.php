<?php

namespace App\Http\Controllers;

use App\Models\Ciudades;
use App\Models\Departamentos;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CiudadesController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim((string) $request->query('search', ''));

        return Inertia::render('Catalogos/Index', [
            'title' => 'Ciudades',
            'description' => 'Consulta y administra las ciudades de operación.',
            'resource' => 'ciudades',
            'createUrl' => route('ciudades.create'),
            'records' => Ciudades::query()
                ->with('departamento:id,nombre')
                ->when($search, fn ($query) => $query->where(function ($query) use ($search) {
                    $query->where('nombre', 'like', "%{$search}%")
                        ->orWhereHas('departamento', fn ($department) => $department->where('nombre', 'like', "%{$search}%"));
                }))
                ->when($request->query('departamento'), fn ($query, $department) => $query->where('id_departamento', $department))
                ->orderBy('nombre')
                ->paginate(10)
                ->withQueryString(),
            'columns' => [
                ['key' => 'id', 'label' => 'ID'],
                ['key' => 'nombre', 'label' => 'Ciudad'],
                ['key' => 'departamento.nombre', 'label' => 'Departamento'],
            ],
            'search' => $search,
            'filters' => [
                'departamento' => $request->query('departamento', ''),
                'departamentos' => Departamentos::query()->orderBy('nombre')->get(['id', 'nombre']),
            ],
        ]);
    }

    public function create(): Response
    {
        return $this->form();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'id_departamento' => ['required', 'integer', 'exists:departamentos,id'],
        ]);

        Ciudades::query()->create($validated);

        return to_route('ciudades.index')->with('success', 'Ciudad creada correctamente.');
    }

    public function edit(Ciudades $ciudad): Response
    {
        return $this->form($ciudad);
    }

    public function update(Request $request, Ciudades $ciudad)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'id_departamento' => ['required', 'integer', 'exists:departamentos,id'],
        ]);

        $ciudad->update($validated);

        return to_route('ciudades.index')->with('success', 'Ciudad actualizada correctamente.');
    }

    public function destroy(Ciudades $ciudad)
    {
        if ($ciudad->sucursales()->exists()) {
            return back()->with('error', 'No se puede eliminar la ciudad porque tiene sucursales asociadas.');
        }

        $ciudad->delete();

        return to_route('ciudades.index')->with('success', 'Ciudad eliminada correctamente.');
    }

    public function show(Ciudades $ciudad): Response
    {
        $ciudad->load('departamento:id,nombre');

        return Inertia::render('Catalogos/Show', [
            'title' => 'Detalle de la ciudad',
            'resource' => 'ciudades',
            'editUrl' => route('ciudades.edit', $ciudad),
            'records' => [
                ['label' => 'ID', 'value' => $ciudad->id],
                ['label' => 'Nombre', 'value' => $ciudad->nombre],
                ['label' => 'Departamento', 'value' => $ciudad->departamento?->nombre],
                ['label' => 'Descripción', 'value' => $ciudad->descripcion],
                ['label' => 'Sucursales relacionadas', 'value' => $ciudad->sucursales()->count()],
            ],
        ]);
    }

    private function form(?Ciudades $ciudad = null): Response
    {
        $editing = $ciudad !== null;

        return Inertia::render('Catalogos/Form', [
            'title' => $editing ? 'Editar ciudad' : 'Crear ciudad',
            'resource' => 'ciudades',
            'action' => $editing ? route('ciudades.update', $ciudad) : route('ciudades.store'),
            'method' => $editing ? 'put' : 'post',
            'cancelUrl' => route('ciudades.index'),
            'fields' => [
                ['name' => 'nombre', 'label' => 'Nombre', 'required' => true],
                ['name' => 'id_departamento', 'label' => 'Departamento', 'type' => 'select', 'required' => true,
                    'options' => Departamentos::query()->orderBy('nombre')->get(['id', 'nombre'])
                        ->map(fn (Departamentos $item) => ['value' => $item->id, 'label' => $item->nombre])],
                ['name' => 'descripcion', 'label' => 'Descripción', 'type' => 'textarea'],
            ],
            'values' => $editing
                ? $ciudad->only(['nombre', 'id_departamento', 'descripcion'])
                : ['nombre' => '', 'id_departamento' => '', 'descripcion' => ''],
        ]);
    }
}
