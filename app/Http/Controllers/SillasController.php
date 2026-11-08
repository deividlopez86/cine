<?php

namespace App\Http\Controllers;

use App\Models\Funciones;
use App\Models\Sillas;
use Illuminate\Http\Request;

class SillasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $sillas = Sillas::with('Salas')->get();

        return response()->json($sillas);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function SillasDisponibles($id_funcion)
    {
        $funcion = Funciones::query()->findOrFail($id_funcion);

        $sillas = Sillas::query()
            ->where('sala_id', $funcion->sala_id)
            ->get();

        return response()->json($sillas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Sillas $sillas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sillas $sillas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sillas $sillas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sillas $sillas)
    {
        //
    }
}
