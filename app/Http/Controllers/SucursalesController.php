<?php

namespace App\Http\Controllers;

use App\Models\Ciudades;
use App\Models\Sucursales;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class SucursalesController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim((string) $request->query('search', ''));

        return Inertia::render('Catalogos/Index', [
            'title' => 'Sucursales',
            'description' => 'Administra las sedes y sus datos de contacto.',
            'resource' => 'sucursales',
            'createUrl' => route('sucursales.create'),
            'records' => Sucursales::query()
                ->with('ciudad:id,nombre,id_departamento', 'ciudad.departamento:id,nombre')
                ->withCount('trabajadores')
                ->when($search, fn ($query) => $query->where(function ($query) use ($search) {
                    $query->where('nombre', 'like', "%{$search}%")
                        ->orWhere('direccion', 'like', "%{$search}%")
                        ->orWhereHas('ciudad', fn ($city) => $city->where('nombre', 'like', "%{$search}%"));
                }))
                ->when($request->query('estado'), fn ($query, $estado) => $query->where('estado', $estado))
                ->orderBy('nombre')
                ->paginate(10)
                ->withQueryString(),
            'columns' => [
                ['key' => 'id', 'label' => 'ID'],
                ['key' => 'nombre', 'label' => 'Sucursal'],
                ['key' => 'ciudad.nombre', 'label' => 'Ciudad'],
                ['key' => 'ciudad.departamento.nombre', 'label' => 'Departamento'],
                ['key' => 'direccion', 'label' => 'Dirección'],
                ['key' => 'telefono', 'label' => 'Teléfono'],
                ['key' => 'email', 'label' => 'Correo'],
                ['key' => 'estado', 'label' => 'Estado'],
                ['key' => 'trabajadores_count', 'label' => 'Trabajadores'],
            ],
            'search' => $search,
            'filters' => ['estado' => $request->query('estado', '')],
        ]);
    }

    public function create(): Response
    {
        return $this->form();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100', Rule::unique('sucursales', 'nombre')],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'id_ciudad' => ['required', 'integer', 'exists:ciudades,id'],
            'direccion' => ['required', 'string', 'max:200'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'estado' => ['required', 'in:activo,inactivo'],
        ]);

        Sucursales::query()->create($validated);

        return to_route('sucursales.index')->with('success', 'Sucursal creada correctamente.');
    }

    public function edit(Sucursales $sucursal): Response
    {
        return $this->form($sucursal);
    }

    public function update(Request $request, Sucursales $sucursal)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100', Rule::unique('sucursales', 'nombre')->ignore($sucursal->id)],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'id_ciudad' => ['required', 'integer', 'exists:ciudades,id'],
            'direccion' => ['required', 'string', 'max:200'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'estado' => ['required', 'in:activo,inactivo'],
        ]);

        $sucursal->update($validated);

        return to_route('sucursales.index')->with('success', 'Sucursal actualizada correctamente.');
    }

    public function destroy(Sucursales $sucursal)
    {
        if ($sucursal->trabajadores()->exists()) {
            return back()->with('error', 'No se puede eliminar la sucursal porque tiene trabajadores asociados.');
        }

        $sucursal->delete();

        return to_route('sucursales.index')->with('success', 'Sucursal eliminada correctamente.');
    }

    public function show(Sucursales $sucursal): Response
    {
        $sucursal->load('ciudad.departamento');

        return Inertia::render('Catalogos/Show', [
            'title' => 'Detalle de la sucursal',
            'resource' => 'sucursales',
            'editUrl' => route('sucursales.edit', $sucursal),
            'records' => [
                ['label' => 'ID', 'value' => $sucursal->id],
                ['label' => 'Nombre', 'value' => $sucursal->nombre],
                ['label' => 'Ciudad', 'value' => $sucursal->ciudad?->nombre],
                ['label' => 'Departamento', 'value' => $sucursal->ciudad?->departamento?->nombre],
                ['label' => 'Dirección', 'value' => $sucursal->direccion],
                ['label' => 'Teléfono', 'value' => $sucursal->telefono],
                ['label' => 'Correo', 'value' => $sucursal->email],
                ['label' => 'Estado', 'value' => $sucursal->estado],
                ['label' => 'Descripción', 'value' => $sucursal->descripcion],
                ['label' => 'Trabajadores relacionados', 'value' => $sucursal->trabajadores()->count()],
            ],
        ]);
    }

    private function form(?Sucursales $sucursal = null): Response
    {
        $editing = $sucursal !== null;

        return Inertia::render('Catalogos/Form', [
            'title' => $editing ? 'Editar sucursal' : 'Crear sucursal',
            'resource' => 'sucursales',
            'action' => $editing ? route('sucursales.update', $sucursal) : route('sucursales.store'),
            'method' => $editing ? 'put' : 'post',
            'cancelUrl' => route('sucursales.index'),
            'fields' => [
                ['name' => 'nombre', 'label' => 'Nombre', 'required' => true],
                ['name' => 'id_ciudad', 'label' => 'Ciudad', 'type' => 'select', 'required' => true,
                    'options' => Ciudades::query()->with('departamento:id,nombre')->orderBy('nombre')->get(['id', 'nombre', 'id_departamento'])
                        ->map(fn (Ciudades $item) => ['value' => $item->id, 'label' => $item->nombre . ' - ' . $item->departamento->nombre])],
                ['name' => 'direccion', 'label' => 'Dirección', 'required' => true],
                ['name' => 'telefono', 'label' => 'Teléfono'],
                ['name' => 'email', 'label' => 'Correo electrónico', 'type' => 'email'],
                ['name' => 'estado', 'label' => 'Estado', 'type' => 'select', 'required' => true,
                    'options' => [
                        ['value' => 'activo', 'label' => 'Activo'],
                        ['value' => 'inactivo', 'label' => 'Inactivo'],
                    ]],
                ['name' => 'descripcion', 'label' => 'Descripción', 'type' => 'textarea'],
            ],
            'values' => $editing
                ? $sucursal->only(['nombre', 'id_ciudad', 'direccion', 'telefono', 'email', 'estado', 'descripcion'])
                : ['nombre' => '', 'id_ciudad' => '', 'direccion' => '', 'telefono' => '', 'email' => '', 'estado' => 'activo', 'descripcion' => ''],
        ]);
    }
}
