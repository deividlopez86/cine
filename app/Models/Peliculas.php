<?php

namespace App\Models {

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Peliculas extends Model
    {
        use HasFactory;

        protected $table = 'peliculas';

        protected $fillable = [
            'titulo',
            'slug',
            'sinopsis',
            'imagen',
            'duracion_minutos',
            'genero',
            'clasificacion',
            'director',
            'anio',
            'fecha_estreno',
            'estado',
        ];

        public const GENEROS = [
            'Acción', 'Aventura', 'Comedia', 'Drama', 'Terror',
            'Ciencia ficción', 'Animación', 'Romance', 'Documental', 'Suspenso',
        ];

        public const CLASIFICACIONES = [
            'ATP' => 'Apta para todo público',
            '+7' => 'Mayores de 7 años',
            '+12' => 'Mayores de 12 años',
            '+15' => 'Mayores de 15 años',
            '+18' => 'Mayores de 18 años',
        ];
        public function Funciones()
        {
            return $this->hasMany(Funciones::class, 'pelicula_id');
        }
    }
}
