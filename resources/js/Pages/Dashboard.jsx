import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

const modules = [
    { title: 'Películas', detail: 'Explora y administra tu catálogo.', href: '/peliculas', metric: 'peliculas', icon: 'film' },
    { title: 'Funciones', detail: 'Consulta la programación de cine.', href: '/funciones', metric: 'funciones', icon: 'calendar' },
    { title: 'Salas', detail: 'Revisa capacidad y disponibilidad.', href: '/salas', metric: 'salas', icon: 'chair' },
    { title: 'Departamentos', detail: 'Organiza las zonas de operación.', href: '/departamentos', metric: 'departamentos', icon: 'building' },
    { title: 'Ciudades', detail: 'Administra las ciudades registradas.', href: '/ciudades', metric: 'ciudades', icon: 'pin' },
    { title: 'Sucursales', detail: 'Gestiona las sedes de tu cine.', href: '/sucursales', metric: 'sucursales', icon: 'store' },
    { title: 'Trabajadores', detail: 'Conoce y administra a tu equipo.', href: '/trabajadores', metric: 'trabajadores', icon: 'users' },
    { title: 'Configuración', detail: 'Actualiza tu perfil y tu cuenta.', href: '/profile', icon: 'settings' },
];

const stats = [
    { title: 'Películas', metric: 'peliculas', href: '/peliculas', icon: 'film', color: 'violet' },
    { title: 'Funciones', metric: 'funciones', href: '/funciones', icon: 'calendar', color: 'blue' },
    { title: 'Salas', metric: 'salas', href: '/salas', icon: 'chair', color: 'pink' },
    { title: 'Departamentos', metric: 'departamentos', href: '/departamentos', icon: 'building', color: 'indigo' },
    { title: 'Ciudades', metric: 'ciudades', href: '/ciudades', icon: 'pin', color: 'cyan' },
    { title: 'Sucursales', metric: 'sucursales', href: '/sucursales', icon: 'store', color: 'purple' },
    { title: 'Trabajadores', metric: 'trabajadores', href: '/trabajadores', icon: 'users', color: 'sky' },
];

const statColors = {
    violet: 'bg-violet-500/10 text-violet-300 ring-violet-400/10',
    blue: 'bg-blue-500/10 text-blue-300 ring-blue-400/10',
    pink: 'bg-pink-500/10 text-pink-300 ring-pink-400/10',
    indigo: 'bg-indigo-500/10 text-indigo-300 ring-indigo-400/10',
    cyan: 'bg-cyan-500/10 text-cyan-300 ring-cyan-400/10',
    purple: 'bg-purple-500/10 text-purple-300 ring-purple-400/10',
    sky: 'bg-sky-500/10 text-sky-300 ring-sky-400/10',
};

function Icon({ name, className = 'h-5 w-5' }) {
    const paths = {
        home: <><path d="m3 10 9-7 9 7" /><path d="M5 9v12h14V9M9 21v-7h6v7" /></>,
        film: <><path d="M4 3h16v18H4zM4 8h16M4 16h16M8 3v5m8-5v5M8 16v5m8-5v5" /></>,
        calendar: <><rect x="3" y="5" width="18" height="16" rx="2" /><path d="M16 3v4M8 3v4M3 11h18" /></>,
        chair: <><path d="M7 10V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v5M5 10h14v5a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-5ZM7 17v4m10-4v4" /></>,
        building: <><path d="M3 21h18M5 21V5l7-3 7 3v16M9 9h1m4 0h1m-6 4h1m4 0h1m-6 4h1m4 0h1" /></>,
        pin: <><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z" /><circle cx="12" cy="10" r="2.5" /></>,
        store: <><path d="M3 10h18l-1.5-6h-15L3 10ZM5 10v10h14V10M9 20v-6h6v6M3 10a3 3 0 0 0 6 0 3 3 0 0 0 6 0 3 3 0 0 0 6 0" /></>,
        users: <><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" /><circle cx="10" cy="7" r="4" /><path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" /></>,
        settings: <><circle cx="12" cy="12" r="3" /><path d="m19.4 15 .1.1 1.4 1.1-1.4 2.4-1.7-.7a8 8 0 0 1-1.6.9l-.3 1.8h-2.8l-.3-1.8a8 8 0 0 1-1.6-.9l-1.7.7-1.4-2.4 1.4-1.1a7 7 0 0 1 0-1.9l-1.4-1.1 1.4-2.4 1.7.7a8 8 0 0 1 1.6-.9l.3-1.8h2.8l.3 1.8a8 8 0 0 1 1.6.9l1.7-.7 1.4 2.4-1.4 1.1a7 7 0 0 1 0 1.9Z" /></>,
        arrow: <><path d="M7 17 17 7M7 7h10v10" /></>,
    };

    return (
        <svg viewBox="0 0 24 24" className={className} fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
            {paths[name]}
        </svg>
    );
}

function posterUrl(image) {
    if (!image) return '';
    if (/^(https?:)?\/\//i.test(image) || image.startsWith('/')) return image;
    return `/storage/${image.replace(/^storage\//, '')}`;
}

export default function Dashboard({ metrics = {}, ultimasPeliculas = [], ultimasFunciones = [] }) {
    return (
        <AuthenticatedLayout>
            <Head title="Inicio" />
            <section className="cinema-hero relative isolate mb-8 overflow-hidden rounded-[26px] border border-white/[.08] shadow-2xl shadow-black/30">
                <div className="relative z-10 flex min-h-[310px] flex-col justify-end gap-6 p-6 sm:min-h-[350px] sm:p-9 lg:p-11">
                    <div className="max-w-2xl">
                        <div className="inline-flex items-center gap-2 rounded-full border border-white/15 bg-black/25 px-3 py-1.5 text-[10px] font-bold uppercase tracking-[.2em] text-violet-200 backdrop-blur-md">
                            <span className="h-1.5 w-1.5 rounded-full bg-violet-400 shadow-[0_0_12px_2px_rgba(167,139,250,.8)]" />
                            Tu universo cinematográfico
                        </div>
                        <p className="mt-6 text-xs font-semibold uppercase tracking-[.3em] text-slate-300">CINE · Panel de control</p>
                        <h2 className="mt-2 text-3xl font-semibold tracking-tight text-white sm:text-4xl lg:text-5xl">
                            Administra tu <span className="cinema-text-gradient">universo CINE</span>
                        </h2>
                        <p className="mt-3 max-w-xl text-sm leading-6 text-slate-300/85">
                            Un resumen en tiempo real de tus películas, programación, salas y equipo.
                        </p>
                    </div>
                    <Link href="/peliculas/create" className="w-fit rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-950/40 transition hover:brightness-110">
                        Registrar película
                    </Link>
                </div>
            </section>

            <section aria-label="Indicadores administrativos" className="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4">
                {stats.map((item) => (
                    <Link key={item.metric} href={item.href} className="group flex items-center gap-4 rounded-2xl border border-white/[.07] bg-[#151923] p-4 transition hover:-translate-y-0.5 hover:border-violet-400/30 hover:bg-[#191e29]">
                        <span className={`flex h-11 w-11 shrink-0 items-center justify-center rounded-xl ring-1 ${statColors[item.color]}`}>
                            <Icon name={item.icon} />
                        </span>
                        <span className="min-w-0">
                            <span className="block text-xs text-slate-400">{item.title} registradas</span>
                            <span className="mt-1 block text-2xl font-semibold tabular-nums text-white">{metrics[item.metric] ?? 0}</span>
                        </span>
                        <Icon name="arrow" className="ml-auto h-4 w-4 text-slate-600 transition group-hover:text-violet-200" />
                    </Link>
                ))}
            </section>

            <section aria-label="Accesos rápidos" className="mt-10">
                <div className="mb-5">
                    <p className="text-[10px] font-bold uppercase tracking-[.22em] text-violet-300">Tu espacio de trabajo</p>
                    <h2 className="mt-1 text-xl font-semibold tracking-tight text-white sm:text-2xl">Accesos rápidos</h2>
                </div>
                <div className="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    {modules.map((module) => (
                        <Link key={module.title} href={module.href} className="group rounded-2xl border border-white/[.07] bg-[#11141d] p-4 transition hover:-translate-y-1 hover:border-violet-400/30 hover:bg-[#151923]">
                            <span className="flex items-center justify-between">
                                <span className="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-500/10 text-violet-200"><Icon name={module.icon} /></span>
                                {module.metric && <span className="text-sm font-semibold tabular-nums text-slate-300">{metrics[module.metric] ?? 0}</span>}
                            </span>
                            <span className="mt-4 block font-semibold text-slate-100">{module.title}</span>
                            <span className="mt-1 block text-xs leading-5 text-slate-400">{module.detail}</span>
                        </Link>
                    ))}
                </div>
            </section>

            <section className="mt-10 grid gap-6 xl:grid-cols-2">
                <div className="overflow-hidden rounded-2xl border border-white/[.07] bg-[#11141d]">
                    <div className="flex items-center justify-between border-b border-white/[.06] px-5 py-4">
                        <div>
                            <p className="text-[10px] font-bold uppercase tracking-[.18em] text-violet-300">Catálogo</p>
                            <h2 className="mt-1 font-semibold text-white">Últimas películas</h2>
                        </div>
                        <Link href="/peliculas" className="text-xs font-medium text-violet-300 hover:text-white">Ver catálogo</Link>
                    </div>
                    <div className="divide-y divide-white/[.05]">
                        {ultimasPeliculas.length ? ultimasPeliculas.map((movie) => (
                            <Link key={movie.id} href={`/peliculas/${movie.id}`} className="flex items-center gap-3 px-5 py-3 transition hover:bg-white/[.03]">
                                {posterUrl(movie.imagen) ? <img src={posterUrl(movie.imagen)} alt="" className="h-12 w-9 rounded-md object-cover" /> : <span className="flex h-12 w-9 items-center justify-center rounded-md bg-violet-500/10 text-violet-200"><Icon name="film" className="h-4 w-4" /></span>}
                                <span className="min-w-0 flex-1">
                                    <span className="block truncate text-sm font-medium text-slate-100">{movie.titulo}</span>
                                    <span className="mt-1 block text-xs text-slate-500">{[movie.genero, movie.anio].filter(Boolean).join(' · ') || 'Película'}</span>
                                </span>
                                <Icon name="arrow" className="h-4 w-4 text-slate-600" />
                            </Link>
                        )) : <p className="px-5 py-8 text-sm text-slate-500">Aún no hay películas registradas.</p>}
                    </div>
                </div>

                <div className="overflow-hidden rounded-2xl border border-white/[.07] bg-[#11141d]">
                    <div className="flex items-center justify-between border-b border-white/[.06] px-5 py-4">
                        <div>
                            <p className="text-[10px] font-bold uppercase tracking-[.18em] text-cyan-300">Programación</p>
                            <h2 className="mt-1 font-semibold text-white">Últimas funciones</h2>
                        </div>
                        <Link href="/funciones" className="text-xs font-medium text-cyan-300 hover:text-white">Ver programación</Link>
                    </div>
                    <div className="divide-y divide-white/[.05]">
                        {ultimasFunciones.length ? ultimasFunciones.map((showtime) => (
                            <div key={showtime.id} className="flex items-center gap-3 px-5 py-4">
                                <span className="flex h-10 w-10 shrink-0 flex-col items-center justify-center rounded-xl bg-cyan-400/10 text-cyan-200">
                                    <Icon name="calendar" className="h-4 w-4" />
                                </span>
                                <span className="min-w-0 flex-1">
                                    <span className="block truncate text-sm font-medium text-slate-100">{showtime.pelicula?.titulo || 'Película'}</span>
                                    <span className="mt-1 block truncate text-xs text-slate-500">{showtime.fecha} · {showtime.hora_inicio?.slice(0, 5)} · {showtime.sala?.nombre || 'Sala'}</span>
                                </span>
                            </div>
                        )) : <p className="px-5 py-8 text-sm text-slate-500">Aún no hay funciones programadas.</p>}
                    </div>
                </div>
            </section>
        </AuthenticatedLayout>
    );
}
