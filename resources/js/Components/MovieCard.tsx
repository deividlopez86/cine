import { Link } from '@inertiajs/react';
import { useState } from 'react';

export type Movie = {
    id: number;
    titulo: string;
    slug?: string | null;
    director?: string | null;
    anio?: number | null;
    genero: string;
    duracion_minutos?: number | null;
    clasificacion?: string | null;
    sinopsis?: string | null;
    imagen?: string | null;
};

export function posterUrl(image?: string | null) {
    if (!image) return '';
    if (/^(https?:)?\/\//i.test(image) || image.startsWith('/')) return image;
    return `/storage/${image.replace(/^storage\//, '')}`;
}

export default function MovieCard({ movie }: { movie: Movie }) {
    const [imageFailed, setImageFailed] = useState(false);
    const poster = posterUrl(movie.imagen);

    return (
        <article className="group min-w-0">
            <Link href={`/peliculas/${movie.id}`} className="relative block aspect-[2/3] overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-br from-[#16313a] via-[#181a29] to-[#111216] shadow-lg shadow-black/20">
                {!imageFailed && poster ? (
                    <img src={poster} alt={`Póster de ${movie.titulo}`} loading="lazy" onError={() => setImageFailed(true)} className="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-105" />
                ) : (
                    <div className="absolute inset-0 flex items-center justify-center bg-[radial-gradient(ellipse_at_30%_20%,rgba(34,211,238,.18),transparent_45%),linear-gradient(145deg,#172e38,#1d1b32_55%,#111216)]">
                        <span className="px-5 text-center text-sm font-bold uppercase tracking-[.2em] text-white/30">{movie.titulo}</span>
                    </div>
                )}
                <div className="absolute inset-0 bg-gradient-to-t from-[#090a0d] via-transparent to-black/10 opacity-90" />
                {movie.clasificacion && <span className="absolute right-3 top-3 rounded-md border border-white/20 bg-black/55 px-2 py-1 text-[10px] font-semibold text-white backdrop-blur">{movie.clasificacion}</span>}
                <span className="absolute bottom-4 left-4 rounded-full border border-white/15 bg-black/50 px-3 py-1.5 text-[11px] font-semibold text-white opacity-0 backdrop-blur transition group-hover:opacity-100">Ver detalles</span>
            </Link>
            <div className="pt-3">
                <h3 className="truncate text-sm font-semibold text-white" title={movie.titulo}>{movie.titulo}</h3>
                <p className="mt-1.5 flex flex-wrap items-center gap-x-2 text-xs text-slate-500">
                    {movie.genero && <span>{movie.genero}</span>}
                    {movie.anio && <><span className="text-slate-700">·</span><span>{movie.anio}</span></>}
                    {movie.duracion_minutos && <><span className="text-slate-700">·</span><span>{movie.duracion_minutos} min</span></>}
                </p>
            </div>
        </article>
    );
}
