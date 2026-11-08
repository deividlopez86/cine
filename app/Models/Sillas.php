<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sillas extends Model
{
    use HasFactory;
    protected $fillable = [
        'sala_id',
        'fila',
        'numero',
        'codigo',
    ];

    public function Salas()
    {
        return $this->belongsTo(Salas::class, 'sala_id');
    }

}
