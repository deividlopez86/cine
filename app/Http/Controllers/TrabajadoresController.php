<?php

namespace App\Http\Controllers;

use App\Models\Sucursales;
use App\Models\Trabajadores;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class TrabajadoresController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim((string) $request->query('search', ''));

        return Inertia::render('Catalogos/Index', [
            'title' => 'Trabajadores',
            'description' => 'Consulta el personal registrado en cada sucursal.',
            'resource' => 'trabajadores',
            'createUrl' => route('trabajadores.create'),
            'records' => Trabajadores::query()
                ->with('sucursal:id,nombre')
                ->when($search, fn ($query) => $query->where(function ($query) use ($search) {
                    $query->where('nombre', 'like', "%{$search}%")
                        ->orWhere('apellido', 'like', "%{$search}%")
                        ->orWhere('documento', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('sucursal', fn ($branch) => $branch->where('nombre', 'like', "%{$search}%"));
                }))
                ->when($request->query('estado'), fn ($query, $estado) => $query->where('estado', $estado))
                ->when($request->query('sucursal'), fn ($query, $sucursal) => $query->where('id_sucursal', $sucursal))
                ->orderBy('apellido')
                ->orderBy('nombre')
                ->paginate(10)
                ->withQueryString(),
            'columns' => [
                ['key' => 'id', 'label' => 'ID'],
                ['key' => 'nombre_completo', 'label' => 'Trabajador'],
                ['key' => 'documento', 'label' => 'Documento'],
                ['key' => 'cargo', 'label' => 'Cargo'],
                ['key' => 'email', 'label' => 'Correo'],
                ['key' => 'sucursal.nombre', 'label' => 'Sucursal'],
                ['key' => 'estado', 'label' => 'Estado'],
            ],
            'search' => $search,
            'filters' => [
                'estado' => $request->query('estado', ''),
                'sucursal' => $request->query('sucursal', ''),
                'sucursales' => Sucursales::query()->orderBy('nombre')->get(['id', 'nombre']),
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
            'apellido' => ['required', 'string', 'max:100'],
            'documento' => ['required', 'string', 'max:30', Rule::unique('trabajadores', 'documento')],
            'tipo_documento' => ['required', 'string', 'max:30'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:200'],
            'email' => ['required', 'email', 'max:150', Rule::unique('trabajadores', 'email')],
            'cargo' => ['required', 'string', 'max:100'],
            'fecha_contratacion' => ['nullable', 'date'],
            'estado' => ['required', 'in:activo,inactivo'],
            'id_sucursal' => ['required', 'integer', 'exists:sucursales,id'],
        ]);

        Trabajadores::query()->create($validated);

        return to_route('trabajadores.index')->with('success', 'Trabajador creado correctamente.');
    }

    public function edit(Trabajadores $trabajador): Response
    {
        return $this->form($trabajador);
    }

    public function update(Request $request, Trabajadores $trabajador)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'documento' => ['required', 'string', 'max:30', Rule::unique('trabajadores', 'documento')->ignore($trabajador->id)],
            'tipo_documento' => ['required', 'string', 'max:30'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:200'],
            'email' => ['required', 'email', 'max:150', Rule::unique('trabajadores', 'email')->ignore($trabajador->id)],
            'cargo' => ['required', 'string', 'max:100'],
            'fecha_contratacion' => ['nullable', 'date'],
            'estado' => ['required', 'in:activo,inactivo'],
            'id_sucursal' => ['required', 'integer', 'exists:sucursales,id'],
        ]);

        $trabajador->update($validated);

        return to_route('trabajadores.index')->with('success', 'Trabajador actualizado correctamente.');
    }

    public function destroy(Trabajadores $trabajador)
    {
        $trabajador->delete();

        return to_route('trabajadores.index')->with('success', 'Trabajador eliminado correctamente.');
    }

    public function show(Trabajadores $trabajador): Response
    {
        $trabajador->load('sucursal:id,nombre');

        return Inertia::render('Catalogos/Show', [
            'title' => 'Detalle del trabajador',
            'resource' => 'trabajadores',
            'editUrl' => route('trabajadores.edit', $trabajador),
            'records' => [
                ['label' => 'ID', 'value' => $trabajador->id],
                ['label' => 'Nombres', 'value' => $trabajador->nombre],
                ['label' => 'Apellidos', 'value' => $trabajador->apellido],
                ['label' => 'Tipo de documento', 'value' => $trabajador->tipo_documento],
                ['label' => 'Documento', 'value' => $trabajador->documento],
                ['label' => 'Correo', 'value' => $trabajador->email],
                ['label' => 'Teléfono', 'value' => $trabajador->telefono],
                ['label' => 'Dirección', 'value' => $trabajador->direccion],
                ['label' => 'Cargo', 'value' => $trabajador->cargo],
                ['label' => 'Fecha de contratación', 'value' => $trabajador->fecha_contratacion?->format('Y-m-d')],
                ['label' => 'Sucursal', 'value' => $trabajador->sucursal?->nombre],
                ['label' => 'Estado', 'value' => $trabajador->estado],
            ],
        ]);
    }

    private function form(?Trabajadores $trabajador = null): Response
    {
        $editing = $trabajador !== null;

        return Inertia::render('Catalogos/Form', [
            'title' => $editing ? 'Editar trabajador' : 'Crear trabajador',
            'resource' => 'trabajadores',
            'action' => $editing ? route('trabajadores.update', $trabajador) : route('trabajadores.store'),
            'method' => $editing ? 'put' : 'post',
            'cancelUrl' => route('trabajadores.index'),
            'fields' => [
                ['name' => 'nombre', 'label' => 'Nombres', 'required' => true],
                ['name' => 'apellido', 'label' => 'Apellidos', 'required' => true],
                ['name' => 'tipo_documento', 'label' => 'Tipo de documento', 'type' => 'select', 'required' => true,
                    'options' => [
                        ['value' => 'CC', 'label' => 'Cédula de ciudadanía'],
                        ['value' => 'CE', 'label' => 'Cédula de extranjería'],
                        ['value' => 'Pasaporte', 'label' => 'Pasaporte'],
                    ]],
                ['name' => 'documento', 'label' => 'Número de documento', 'required' => true],
                ['name' => 'telefono', 'label' => 'Teléfono'],
                ['name' => 'direccion', 'label' => 'Dirección'],
                ['name' => 'email', 'label' => 'Correo electrónico', 'type' => 'email', 'required' => true],
                ['name' => 'cargo', 'label' => 'Cargo', 'required' => true],
                ['name' => 'fecha_contratacion', 'label' => 'Fecha de contratación', 'type' => 'date'],
                ['name' => 'estado', 'label' => 'Estado', 'type' => 'select', 'required' => true,
                    'options' => [
                        ['value' => 'activo', 'label' => 'Activo'],
                        ['value' => 'inactivo', 'label' => 'Inactivo'],
                    ]],
                ['name' => 'id_sucursal', 'label' => 'Sucursal', 'type' => 'select', 'required' => true,
                    'options' => Sucursales::query()->orderBy('nombre')->get(['id', 'nombre'])
                        ->map(fn (Sucursales $item) => ['value' => $item->id, 'label' => $item->nombre])],
            ],
            'values' => $editing
                ? $trabajador->only(['nombre', 'apellido', 'tipo_documento', 'documento', 'telefono', 'direccion', 'email', 'cargo', 'fecha_contratacion', 'estado', 'id_sucursal'])
                : ['nombre' => '', 'apellido' => '', 'tipo_documento' => 'CC', 'documento' => '', 'telefono' => '', 'direccion' => '', 'email' => '', 'cargo' => '', 'fecha_contratacion' => '', 'estado' => 'activo', 'id_sucursal' => ''],
        ]);
    }
}
