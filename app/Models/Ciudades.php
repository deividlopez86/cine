<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ciudades extends Model
{
    use HasFactory;

    protected $table = 'ciudades';

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_departamento',
    ];

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamentos::class, 'id_departamento');
    }

    public function sucursales(): HasMany
    {
        return $this->hasMany(Sucursales::class, 'id_ciudad');
    }
}
