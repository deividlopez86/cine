import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { posterUrl, type Movie } from '../../Components/MovieCard';

type PaginationLink = { url: string | null; label: string; active: boolean };
type Props = {
    peliculas: { data: Movie[]; links: PaginationLink[]; current_page: number; last_page: number; total: number };
    generos: string[];
    years: number[];
    filters: { search: string; genre: string; year: string };
    auth?: { user?: { name: string } | null };
};

export default function Index({ peliculas, generos, years, filters, auth }: Props) {
    const [deleting, setDeleting] = useState<number | null>(null);

    function remove(movie: Movie) {
        if (!window.confirm(`¿Eliminar “${movie.titulo}”? Esta acción no se puede deshacer.`)) return;
        setDeleting(movie.id);
        router.delete(`/peliculas/${movie.id}`, {
            preserveScroll: true,
            onFinish: () => setDeleting(null),
        });
    }

    return (
        <AuthenticatedLayout>
            <Head title="Catálogo de películas" />
            <main className="mx-auto min-h-[70vh] max-w-[1440px] px-5 py-10 sm:px-10 sm:py-14">
                <div className="flex flex-wrap items-end justify-between gap-5">
                    <div>
                        <p className="text-[10px] font-bold uppercase tracking-[.24em] text-cyan-300">Tu catálogo cinematográfico</p>
                        <h1 className="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">Películas</h1>
                        <p className="mt-2 text-sm text-slate-400">{peliculas.total} {peliculas.total === 1 ? 'título' : 'títulos'} en el catálogo</p>
                    </div>
                    {auth?.user && <Link href="/peliculas/create" className="rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-violet-950/20 transition hover:brightness-110">＋ Agregar película</Link>}
                </div>

                <form action="/peliculas" method="get" className="mt-8 grid gap-3 rounded-2xl border border-white/[.08] bg-white/[.025] p-4 sm:grid-cols-[1fr_190px_140px_auto]">
                    <input type="search" name="search" defaultValue={filters.search} aria-label="Buscar por título" placeholder="Buscar película por título..." className="rounded-xl border border-white/10 bg-[#101116] px-4 py-3 text-sm text-white placeholder:text-slate-600 focus:border-cyan-300/50 focus:ring-cyan-300/20" />
                    <select name="genre" defaultValue={filters.genre} aria-label="Filtrar por género" className="rounded-xl border border-white/10 bg-[#101116] px-4 py-3 text-sm text-slate-200 focus:border-cyan-300/50 focus:ring-cyan-300/20">
                        <option value="">Todos los géneros</option>
                        {generos.map((genre) => <option key={genre} value={genre}>{genre}</option>)}
                    </select>
                    <select name="year" defaultValue={filters.year} aria-label="Filtrar por año" className="rounded-xl border border-white/10 bg-[#101116] px-4 py-3 text-sm text-slate-200 focus:border-cyan-300/50 focus:ring-cyan-300/20">
                        <option value="">Todos los años</option>
                        {years.map((year) => <option key={year} value={year}>{year}</option>)}
                    </select>
                    <button className="rounded-xl bg-white/[.08] px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/[.14]" type="submit">Buscar</button>
                </form>

                {peliculas.data.length ? (
                    <>
                        <div className="mt-7 overflow-hidden rounded-2xl border border-white/[.08]">
                            <div className="hidden grid-cols-[minmax(240px,2fr)_1fr_100px_140px] gap-4 bg-white/[.04] px-5 py-3 text-[10px] font-bold uppercase tracking-[.15em] text-slate-500 md:grid">
                                <span>Película</span><span>Director</span><span>Año</span><span className="text-right">Acciones</span>
                            </div>
                            <div className="divide-y divide-white/[.06]">
                                {peliculas.data.map((movie) => (
                                    <article key={movie.id} className="grid gap-3 px-4 py-4 transition hover:bg-white/[.025] md:grid-cols-[minmax(240px,2fr)_1fr_100px_140px] md:items-center md:gap-4 md:px-5">
                                        <div className="flex min-w-0 items-center gap-4">
                                            <div className="h-16 w-11 shrink-0 overflow-hidden rounded-lg bg-gradient-to-br from-cyan-950 to-violet-950">
                                                {movie.imagen && <img src={posterUrl(movie.imagen)} alt="" className="h-full w-full object-cover" onError={(event) => { event.currentTarget.style.display = 'none'; }} />}
                                            </div>
                                            <div className="min-w-0">
                                                <Link href={`/peliculas/${movie.id}`} className="truncate font-semibold text-white hover:text-cyan-200">{movie.titulo}</Link>
                                                <p className="mt-1 text-xs text-slate-500">{movie.genero} {movie.clasificacion && `· ${movie.clasificacion}`}</p>
                                            </div>
                                        </div>
                                        <span className="text-sm text-slate-400">{movie.director || '—'}</span>
                                        <span className="text-sm text-slate-400">{movie.anio || '—'}</span>
                                        <div className="flex items-center justify-start gap-2 md:justify-end">
                                            <Link aria-label={`Ver ${movie.titulo}`} title="Ver" href={`/peliculas/${movie.id}`} className="rounded-lg border border-white/10 px-2.5 py-2 text-xs text-slate-300 hover:border-cyan-300/30 hover:text-cyan-200">Ver</Link>
                                            {auth?.user && <>
                                                <Link aria-label={`Editar ${movie.titulo}`} title="Editar" href={`/peliculas/${movie.id}/edit`} className="rounded-lg border border-white/10 px-2.5 py-2 text-xs text-slate-300 hover:border-cyan-300/30 hover:text-cyan-200">Editar</Link>
                                                <button aria-label={`Eliminar ${movie.titulo}`} title="Eliminar" disabled={deleting === movie.id} onClick={() => remove(movie)} className="rounded-lg border border-rose-300/15 px-2.5 py-2 text-xs text-rose-200 hover:bg-rose-300/10 disabled:opacity-50" type="button">{deleting === movie.id ? '...' : 'Borrar'}</button>
                                            </>}
                                        </div>
                                    </article>
                                ))}
                            </div>
                        </div>
                        {peliculas.last_page > 1 && <nav aria-label="Paginación" className="mt-8 flex flex-wrap justify-center gap-2">
                            {peliculas.links.map((link, index) => link.url ? (
                                <Link key={`${link.label}-${index}`} href={link.url} preserveScroll className={`rounded-lg px-3 py-2 text-sm ${link.active ? 'bg-violet-600 text-white' : 'border border-white/10 text-slate-300 hover:bg-white/[.06]'}`} dangerouslySetInnerHTML={{ __html: link.label }} />
                            ) : <span key={`${link.label}-${index}`} className="rounded-lg px-3 py-2 text-sm text-slate-600" dangerouslySetInnerHTML={{ __html: link.label }} />)}
                        </nav>}
                    </>
                ) : (
                    <div className="mt-8 rounded-2xl border border-dashed border-white/10 px-6 py-16 text-center">
                        <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-300/[.08] text-2xl text-cyan-200">✦</div>
                        <h2 className="mt-5 text-lg font-semibold text-white">{filters.search || filters.genre || filters.year ? 'No encontramos películas con esos filtros' : 'Tu catálogo empieza aquí'}</h2>
                        <p className="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-400">{filters.search || filters.genre || filters.year ? 'Prueba con otros términos o elimina alguno de los filtros.' : 'Todavía no hay películas registradas. Agrega el primer título para comenzar.'}</p>
                        {auth?.user && <Link href="/peliculas/create" className="mt-6 inline-flex rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-3 text-sm font-bold text-white">Agregar película</Link>}
                    </div>
                )}
            </main>
        </AuthenticatedLayout>
    );
}
