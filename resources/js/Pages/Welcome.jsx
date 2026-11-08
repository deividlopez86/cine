import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { useEffect, useRef, useState } from 'react';

const genreArtwork = {
    'Acción': 'photo-1519608487953-e999c86e7455',
    'Aventura': 'photo-1464822759023-fed622ff2c3b',
    'Terror': 'photo-1509248961158-e54f6934749c',
    'Romance': 'photo-1516589178581-6cd7833ae3b2',
    'Ciencia ficción': 'photo-1451187580459-43490279c0fa',
    'Comedia': 'photo-1524368535928-5b5e00ddc76b',
    'Drama': 'photo-1470770841072-f978cf4d019e',
    'Animación': 'photo-1535223289827-42f1e9919769',
    'Suspenso': 'photo-1519608487953-e999c86e7455',
    'Documental': 'photo-1470071459604-3b5ec3a7fe05',
};

function posterUrl(image) {
    if (!image) return '';
    if (/^(https?:)?\/\//i.test(image) || image.startsWith('/')) return image;
    return `/storage/${image.replace(/^storage\//, '')}`;
}

function Icon({ name, className = 'h-5 w-5' }) {
    const paths = {
        play: <path d="m8 5 12 7-12 7V5Z" fill="currentColor" stroke="none" />,
        plus: <><path d="M12 5v14M5 12h14" /></>,
        left: <path d="m15 18-6-6 6-6" />,
        right: <path d="m9 18 6-6-6-6" />,
        film: <><path d="M4 3h16v18H4zM4 8h16M4 16h16M8 3v5m8-5v5M8 16v5m8-5v5" /></>,
        arrow: <><path d="M7 17 17 7M7 7h10v10" /></>,
    };

    return <svg viewBox="0 0 24 24" className={className} fill="none" stroke="currentColor" strokeWidth="1.7" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">{paths[name]}</svg>;
}

function MovieRow({ title, movies, savedIds, onToggleSaved }) {
    const rail = useRef(null);
    if (!movies?.length) return null;

    return (
        <section className="mb-9" aria-label={title}>
            <div className="mb-4 flex items-end justify-between gap-4">
                <div>
                    <p className="text-[10px] font-bold uppercase tracking-[.2em] text-cyan-300">Catálogo CINE</p>
                    <h2 className="mt-1 text-xl font-bold tracking-tight text-white sm:text-2xl">{title}</h2>
                </div>
                <div className="flex items-center gap-2">
                    <button type="button" aria-label={`Desplazar ${title} a la izquierda`} onClick={() => rail.current?.scrollBy({ left: -500, behavior: 'smooth' })} className="rounded-full border border-white/10 bg-white/[.04] p-2 text-slate-300 transition hover:border-violet-400/40 hover:bg-violet-500/15 hover:text-white"><Icon name="left" className="h-4 w-4" /></button>
                    <button type="button" aria-label={`Desplazar ${title} a la derecha`} onClick={() => rail.current?.scrollBy({ left: 500, behavior: 'smooth' })} className="rounded-full border border-white/10 bg-white/[.04] p-2 text-slate-300 transition hover:border-violet-400/40 hover:bg-violet-500/15 hover:text-white"><Icon name="right" className="h-4 w-4" /></button>
                </div>
            </div>
            <div ref={rail} className="movie-rail flex snap-x snap-mandatory gap-4 overflow-x-auto pb-2">
                {movies.map((movie) => {
                    const poster = posterUrl(movie.imagen);
                    return (
                        <article key={movie.id} className="group w-[150px] shrink-0 snap-start sm:w-[185px]">
                            <Link href={`/peliculas/${movie.id}`} className="relative block aspect-[2/3] overflow-hidden rounded-xl border border-white/[.08] bg-[#151923] shadow-xl shadow-black/20 transition duration-300 group-hover:-translate-y-1 group-hover:shadow-violet-950/30">
                                {poster ? <img src={poster} alt={`Póster de ${movie.titulo}`} loading="lazy" className="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105" /> : <div className="absolute inset-0 flex items-center justify-center bg-[radial-gradient(ellipse_at_30%_20%,rgba(139,92,246,.24),transparent_48%),linear-gradient(145deg,#172e38,#1d1b32_55%,#111216)]"><Icon name="film" className="h-9 w-9 text-white/30" /></div>}
                                <span className="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-black/20 opacity-90" />
                                {movie.clasificacion && <span className="absolute right-2 top-2 rounded border border-white/20 bg-black/55 px-1.5 py-1 text-[9px] font-semibold text-white backdrop-blur">{movie.clasificacion}</span>}
                                <span className="absolute bottom-3 left-3 right-3 translate-y-2 text-xs font-semibold text-white opacity-0 transition group-hover:translate-y-0 group-hover:opacity-100">Ver detalles <span aria-hidden="true">→</span></span>
                            </Link>
                            <div className="flex items-start gap-2 pt-3">
                                <div className="min-w-0 flex-1">
                                    <h3 className="truncate text-sm font-semibold text-white" title={movie.titulo}>{movie.titulo}</h3>
                                    <p className="mt-1 truncate text-[11px] text-slate-400">{[movie.genero, movie.anio, movie.duracion_minutos ? `${movie.duracion_minutos} min` : null].filter(Boolean).join(' · ')}</p>
                                </div>
                                <button type="button" onClick={() => onToggleSaved(movie.id)} aria-label={savedIds.includes(movie.id) ? `Quitar ${movie.titulo} de Mi lista` : `Agregar ${movie.titulo} a Mi lista`} title={savedIds.includes(movie.id) ? 'Quitar de Mi lista' : 'Agregar a Mi lista'} className={`rounded-lg p-1.5 transition ${savedIds.includes(movie.id) ? 'bg-violet-500/20 text-violet-200' : 'text-slate-500 hover:bg-white/10 hover:text-white'}`}><Icon name="plus" className={`h-4 w-4 ${savedIds.includes(movie.id) ? 'rotate-45' : ''}`} /></button>
                            </div>
                        </article>
                    );
                })}
            </div>
        </section>
    );
}

export default function Welcome({ peliculas = [], destacada = null, categorias = {}, generos = [] }) {
    const [savedIds, setSavedIds] = useState([]);
    const featuredPoster = posterUrl(destacada?.imagen);

    useEffect(() => {
        try {
            const stored = JSON.parse(window.localStorage.getItem('cine-mi-lista') || '[]');
            if (Array.isArray(stored)) setSavedIds(stored.filter((id) => Number.isInteger(id)));
        } catch {
            setSavedIds([]);
        }
    }, []);

    function toggleSaved(id) {
        setSavedIds((current) => {
            const next = current.includes(id) ? current.filter((savedId) => savedId !== id) : [...current, id];
            window.localStorage.setItem('cine-mi-lista', JSON.stringify(next));
            return next;
        });
    }

    const savedMovies = peliculas.filter((movie) => savedIds.includes(movie.id));
    const categoryRows = Object.entries(categorias);

    return (
        <AuthenticatedLayout>
            <Head title="Inicio" />
            <section className="cinema-hero relative isolate -mx-4 -mt-6 mb-10 min-h-[440px] overflow-hidden sm:-mx-7 sm:-mt-8 lg:-mx-9">
                {featuredPoster && <div className="absolute inset-0 -z-10 bg-cover bg-center opacity-40 blur-[1px]" style={{ backgroundImage: `url("${featuredPoster}")` }} />}
                <div className="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_75%_35%,rgba(93,39,165,.25),transparent_45%)]" />
                <div className="mx-auto flex min-h-[440px] max-w-[1600px] items-end px-4 pb-9 pt-24 sm:px-7 sm:pb-12 lg:px-9">
                    <div className="max-w-3xl">
                        <div className="inline-flex items-center gap-2 rounded-full border border-cyan-300/20 bg-black/30 px-3 py-1.5 text-[10px] font-bold uppercase tracking-[.2em] text-cyan-200 backdrop-blur-md">
                            <span className="h-1.5 w-1.5 rounded-full bg-cyan-300 shadow-[0_0_12px_2px_rgba(103,232,249,.7)]" />
                            En primer plano
                        </div>
                        <p className="mt-6 text-xs font-semibold uppercase tracking-[.25em] text-slate-300">Una historia para la gran pantalla</p>
                        <h2 className="mt-3 text-4xl font-black uppercase leading-[1.05] tracking-tight text-white sm:text-5xl lg:text-6xl">
                            {destacada?.titulo || 'Historias que merecen pantalla grande'}
                        </h2>
                        {destacada ? (
                            <>
                                <div className="mt-4 flex flex-wrap items-center gap-x-3 gap-y-2 text-xs font-medium text-slate-300">
                                    {destacada.anio && <span>{destacada.anio}</span>}
                                    {destacada.genero && <><span className="text-slate-600">·</span><span>{destacada.genero}</span></>}
                                    {destacada.clasificacion && <><span className="text-slate-600">·</span><span>{destacada.clasificacion}</span></>}
                                    {destacada.duracion_minutos && <><span className="text-slate-600">·</span><span>{destacada.duracion_minutos} min</span></>}
                                </div>
                                <p className="mt-4 line-clamp-3 max-w-2xl text-sm leading-6 text-slate-300">{destacada.sinopsis}</p>
                            </>
                        ) : <p className="mt-4 max-w-xl text-sm leading-6 text-slate-300">Descubre el catálogo y administra todas las historias de CINE desde un solo lugar.</p>}
                        <div className="mt-6 flex flex-wrap gap-3">
                            {destacada && <Link href={`/peliculas/${destacada.id}`} className="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-950/40 transition hover:brightness-110"><Icon name="play" className="h-4 w-4" />Ver ficha</Link>}
                            {destacada && <button type="button" onClick={() => toggleSaved(destacada.id)} className="inline-flex items-center gap-2 rounded-xl border border-white/15 bg-white/[.08] px-5 py-3 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/[.14]"><Icon name="plus" className="h-4 w-4" />{savedIds.includes(destacada.id) ? 'En mi lista' : 'Mi lista'}</button>}
                            {!destacada && <Link href="/peliculas" className="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-950/40 transition hover:brightness-110">Explorar películas <Icon name="arrow" className="h-4 w-4" /></Link>}
                        </div>
                    </div>
                </div>
            </section>

            <div className="mx-auto max-w-[1600px]">
                {savedMovies.length > 0 && <MovieRow title="Mi lista" movies={savedMovies} savedIds={savedIds} onToggleSaved={toggleSaved} />}
                {categoryRows.map(([title, movies]) => <MovieRow key={title} title={title} movies={movies} savedIds={savedIds} onToggleSaved={toggleSaved} />)}

                <section className="mb-12 rounded-3xl border border-white/[.07] bg-[radial-gradient(ellipse_at_85%_15%,rgba(91,33,182,.18),transparent_38%),#11141d] p-6 sm:p-9">
                    <div className="grid items-center gap-8 lg:grid-cols-[1.15fr_.85fr]">
                        <div className="relative aspect-video overflow-hidden rounded-2xl border border-white/10 bg-[#08090D] shadow-2xl shadow-black/40">
                            {featuredPoster ? <img src={featuredPoster} alt={`Imagen de ${destacada?.titulo}`} className="absolute inset-0 h-full w-full object-cover opacity-65" /> : <div className="absolute inset-0 cinema-hero opacity-70" />}
                            <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-black/20" />
                            <span className="absolute bottom-4 left-4 text-[10px] font-semibold uppercase tracking-[.18em] text-white/80">Selección CINE</span>
                            {destacada && <Link href={`/peliculas/${destacada.id}`} aria-label={`Abrir ficha de ${destacada.titulo}`} className="absolute inset-0 flex items-center justify-center"><span className="flex h-14 w-14 items-center justify-center rounded-full border border-white/60 bg-white/20 text-white shadow-lg backdrop-blur transition hover:scale-110 hover:bg-violet-500/70"><Icon name="play" className="ml-1 h-6 w-6" /></span></Link>}
                        </div>
                        <div>
                            <p className="text-[10px] font-bold uppercase tracking-[.22em] text-cyan-300">Descubre algo nuevo</p>
                            <h2 className="mt-2 text-2xl font-bold tracking-tight text-white sm:text-3xl">{destacada?.titulo || 'Cine para cada momento'}</h2>
                            <p className="mt-3 text-sm leading-6 text-slate-400">{destacada?.sinopsis || 'Explora el catálogo disponible y encuentra tu próxima película favorita.'}</p>
                            <dl className="mt-5 grid grid-cols-2 gap-3 text-sm">
                                <div className="rounded-xl border border-white/[.06] bg-black/20 p-3"><dt className="text-[10px] uppercase tracking-wider text-slate-500">Dirección</dt><dd className="mt-1 font-medium text-slate-200">{destacada?.director || 'No especificado'}</dd></div>
                                <div className="rounded-xl border border-white/[.06] bg-black/20 p-3"><dt className="text-[10px] uppercase tracking-wider text-slate-500">Género</dt><dd className="mt-1 font-medium text-slate-200">{destacada?.genero || 'Catálogo CINE'}</dd></div>
                            </dl>
                            {destacada && <Link href={`/peliculas/${destacada.id}`} className="mt-5 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:brightness-110">Explorar película <Icon name="arrow" className="h-4 w-4" /></Link>}
                        </div>
                    </div>
                </section>

                {generos.length > 0 && <section id="generos" className="mb-10">
                    <div className="mb-5 flex flex-wrap items-end justify-between gap-3">
                        <div><p className="text-[10px] font-bold uppercase tracking-[.22em] text-violet-300">Encuentra tu próxima historia</p><h2 className="mt-1 text-2xl font-bold tracking-tight text-white">Explorar por género</h2></div>
                        <Link href="/peliculas" className="text-xs font-medium text-slate-400 transition hover:text-white">Todo el catálogo <span aria-hidden="true">→</span></Link>
                    </div>
                    <div className="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
                        {generos.map((genre) => <Link key={genre} href={`/peliculas?genre=${encodeURIComponent(genre)}`} className="group relative flex min-h-28 items-end overflow-hidden rounded-2xl border border-white/[.08] bg-[#151923] p-4 transition hover:-translate-y-1 hover:border-violet-300/40">
                            <img src={`https://images.unsplash.com/${genreArtwork[genre] || 'photo-1489599849927-2ee91cede3ba'}?auto=format&fit=crop&w=600&q=80`} alt="" loading="lazy" className="absolute inset-0 h-full w-full object-cover opacity-45 transition duration-500 group-hover:scale-110 group-hover:opacity-65" />
                            <span className="absolute inset-0 bg-gradient-to-t from-[#08090D] via-black/20 to-transparent" />
                            <span className="relative text-sm font-bold text-white">{genre}</span>
                        </Link>)}
                    </div>
                </section>}

                {!peliculas.length && <section className="rounded-2xl border border-dashed border-white/10 bg-white/[.02] px-6 py-12 text-center">
                    <span className="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-500/10 text-violet-200"><Icon name="film" /></span>
                    <h2 className="mt-4 text-lg font-semibold text-white">Tu catálogo empieza aquí</h2>
                    <p className="mt-2 text-sm text-slate-400">Todavía no hay películas para mostrar.</p>
                    <Link href="/peliculas" className="mt-5 inline-flex rounded-xl border border-white/10 px-4 py-2 text-sm font-semibold text-slate-200 hover:bg-white/[.06]">Ir a películas</Link>
                </section>}
            </div>
        </AuthenticatedLayout>
    );
}
