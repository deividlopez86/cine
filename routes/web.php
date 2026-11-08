<?php

use App\Http\Controllers\CiudadesController;
use App\Http\Controllers\DepartamentosController;
use App\Http\Controllers\FuncionesController;
use App\Http\Controllers\GlobalSearchController;
use App\Http\Controllers\PeliculasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalasController;
use App\Http\Controllers\SillasController;
use App\Http\Controllers\SucursalesController;
use App\Http\Controllers\TrabajadoresController;
use App\Models\Ciudades;
use App\Models\Departamentos;
use App\Models\Funciones;
use App\Models\Peliculas;
use App\Models\Salas;
use App\Models\Sillas;
use App\Models\Sucursales;
use App\Models\Trabajadores;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/peliculas', [PeliculasController::class, 'index'])->name('peliculas.index');

Route::middleware('auth')->group(function () {
    Route::resource('departamentos', DepartamentosController::class);
    Route::resource('ciudades', CiudadesController::class)
        ->parameters(['ciudades' => 'ciudad']);
    Route::resource('sucursales', SucursalesController::class)
        ->parameters(['sucursales' => 'sucursal']);
    Route::resource('trabajadores', TrabajadoresController::class)
        ->parameters(['trabajadores' => 'trabajador']);
    Route::resource('salas', SalasController::class);
    Route::resource('funciones', FuncionesController::class)
        ->parameters(['funciones' => 'funcion']);
    Route::resource('sillas', SillasController::class)
        ->parameters(['sillas' => 'silla']);
    Route::get('/peliculas/create', [PeliculasController::class, 'create'])->name('peliculas.create');
    Route::post('/peliculas', [PeliculasController::class, 'store'])->name('peliculas.store');
    Route::get('/peliculas/{pelicula}/edit', [PeliculasController::class, 'edit'])->name('peliculas.edit');
    Route::put('/peliculas/{pelicula}', [PeliculasController::class, 'update'])->name('peliculas.update');
    Route::delete('/peliculas/{pelicula}', [PeliculasController::class, 'destroy'])->name('peliculas.destroy');
});

Route::get('/peliculas/{pelicula}', [PeliculasController::class, 'show'])->name('peliculas.show');

Route::get('/', function () {
    $peliculas = Peliculas::query()
        ->orderByDesc('id')
        ->limit(24)
        ->get();

    $categorias = collect([
        'Tendencias' => Peliculas::query()->orderByDesc('id')->limit(12)->get(),
        'Estrenos' => Peliculas::query()->orderByDesc('fecha_estreno')->orderByDesc('id')->limit(12)->get(),
        'Acción' => Peliculas::query()->where('genero', 'Acción')->orderByDesc('id')->limit(12)->get(),
        'Ciencia ficción' => Peliculas::query()->where('genero', 'Ciencia ficción')->orderByDesc('id')->limit(12)->get(),
        'Comedia' => Peliculas::query()->where('genero', 'Comedia')->orderByDesc('id')->limit(12)->get(),
    ])->filter(fn ($movies) => $movies->isNotEmpty());

    return Inertia::render('Welcome', [
        'peliculas' => $peliculas,
        'destacada' => $peliculas->first(),
        'categorias' => $categorias,
        'generos' => Peliculas::query()
            ->whereNotNull('genero')
            ->distinct()
            ->orderBy('genero')
            ->pluck('genero'),
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'metrics' => [
            'peliculas' => Peliculas::query()->count(),
            'funciones' => Funciones::query()->count(),
            'salas' => Salas::query()->count(),
            'ciudades' => Ciudades::query()->count(),
            'sucursales' => Sucursales::query()->count(),
            'trabajadores' => Trabajadores::query()->count(),
            'departamentos' => Departamentos::query()->count(),
        ],
        'ultimasPeliculas' => Peliculas::query()
            ->latest('created_at')
            ->limit(5)
            ->get(['id', 'titulo', 'genero', 'anio', 'imagen', 'created_at']),
        'ultimasFunciones' => Funciones::query()
            ->with(['pelicula:id,titulo'])
            ->latest('created_at')
            ->limit(5)
            ->get(['id', 'pelicula_id', 'sala_id', 'fecha', 'hora_inicio', 'hora_fin', 'precio', 'created_at']),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/buscar', GlobalSearchController::class)->name('search');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/peliculasdisponibles', [PeliculasController::class, 'PeliculasDisponibles']);
Route::get('/funcionesdisponibles/{id_pelicula}', [FuncionesController::class, 'FuncionesDisponibles']);
Route::get('/sillasdisponibles/{id_funcion}', [SillasController::class, 'SillasDisponibles']);