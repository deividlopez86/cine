<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Peliculas;

class Funciones extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelicula_id',
        'sala_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'precio',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date:Y-m-d',
            'estado' => 'boolean',
        ];
    }
    public function Peliculas()
    {
        return $this->belongsTo(Peliculas::class, 'pelicula_id');
    }

    public function pelicula(): BelongsTo
    {
        return $this->belongsTo(Peliculas::class, 'pelicula_id');
    }

    public function Salas()
    {
        return $this->belongsTo(Salas::class, 'sala_id');
    }
}
