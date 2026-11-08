<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sucursales extends Model
{
    use HasFactory;

        protected $table = 'sucursales';

        protected $fillable = [
            'nombre',
            'descripcion',
            'id_ciudad',
            'direccion',
            'telefono',
            'email',
            'estado',
        ];

        public function ciudad(): BelongsTo
        {
            return $this->belongsTo(Ciudades::class, 'id_ciudad');
        }

        public function trabajadores(): HasMany
        {
            return $this->hasMany(Trabajadores::class, 'id_sucursal');
        }
}
