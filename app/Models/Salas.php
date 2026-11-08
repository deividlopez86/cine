<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'capacidad',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'estado' => 'boolean',
        ];
    }

    public function Funciones()
    {
        return $this->hasMany(Funciones::class, 'sala_id');
    }
    
}
