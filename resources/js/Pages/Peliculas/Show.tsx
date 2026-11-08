import { Head, Link } from '@inertiajs/react';
import { useState } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { posterUrl, type Movie } from '../../Components/MovieCard';

export default function Show({ pelicula, auth }: { pelicula: Movie; auth?: { user?: { name: string } | null } }) {
    const [imageFailed, setImageFailed] = useState(false);
    const poster = posterUrl(pelicula.imagen);

    return (
        <AuthenticatedLayout>
            <Head title={pelicula.titulo} />
            <main className="mx-auto max-w-[1200px] px-5 py-10 sm:px-10 sm:py-16">
                <Link href="/peliculas" className="text-sm text-slate-400 transition hover:text-cyan-200">← Volver al catálogo</Link>
                <article className="mt-8 grid overflow-hidden rounded-3xl border border-white/[.08] bg-[#111217] md:grid-cols-[minmax(250px,380px)_1fr]">
                    <div className="relative min-h-[380px] bg-gradient-to-br from-cyan-950 via-violet-950 to-[#111216] md:min-h-[600px]">
                        {!imageFailed && poster && <img src={poster} alt={`Póster de ${pelicula.titulo}`} onError={() => setImageFailed(true)} className="absolute inset-0 h-full w-full object-cover" />}
                        <div className="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent md:bg-gradient-to-r" />
                    </div>
                    <div className="flex flex-col justify-center p-7 sm:p-12">
                        <p className="text-xs font-bold uppercase tracking-[.22em] text-cyan-300">{pelicula.genero}</p>
                        <h1 className="mt-4 text-4xl font-black leading-tight tracking-tight sm:text-5xl">{pelicula.titulo}</h1>
                        <div className="mt-5 flex flex-wrap gap-2 text-xs text-slate-300">
                            {pelicula.anio && <span className="rounded-full border border-white/10 px-3 py-1.5">{pelicula.anio}</span>}
                            {pelicula.clasificacion && <span className="rounded-full border border-white/10 px-3 py-1.5">{pelicula.clasificacion}</span>}
                            {pelicula.duracion_minutos && <span className="rounded-full border border-white/10 px-3 py-1.5">{pelicula.duracion_minutos} min</span>}
                        </div>
                        <p className="mt-8 text-sm leading-7 text-slate-300">{pelicula.sinopsis}</p>
                        <dl className="mt-8 grid gap-4 border-t border-white/[.08] pt-6 sm:grid-cols-2">
                            <div><dt className="text-[10px] font-bold uppercase tracking-[.15em] text-slate-500">Dirección</dt><dd className="mt-1 text-sm text-white">{pelicula.director || 'No especificado'}</dd></div>
                            <div><dt className="text-[10px] font-bold uppercase tracking-[.15em] text-slate-500">Género</dt><dd className="mt-1 text-sm text-white">{pelicula.genero}</dd></div>
                        </dl>
                        {auth?.user && <Link href={`/peliculas/${pelicula.id}/edit`} className="mt-8 w-fit rounded-xl border border-white/15 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-white/[.07]">Editar información</Link>}
                    </div>
                </article>
            </main>
        </AuthenticatedLayout>
    );
}
