<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trabajadores extends Model
{
    use HasFactory;

    protected $table = 'trabajadores';

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'direccion',
        'documento',
        'tipo_documento',
        'cargo',
        'fecha_contratacion',
        'estado',
        'id_sucursal',
    ];

    protected function casts(): array
    {
        return [
            'fecha_contratacion' => 'date:Y-m-d',
        ];
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursales::class, 'id_sucursal');
    }
}
