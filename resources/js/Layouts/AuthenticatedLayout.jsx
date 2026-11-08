import { Link, usePage } from '@inertiajs/react';
import { useState } from 'react';
import Footer from '@/Components/Footer';

const navigation = [
    { label: 'Inicio', href: '/dashboard', match: '/dashboard', glyph: 'home' },
    { label: 'Películas', href: '/peliculas', match: '/peliculas', glyph: 'film' },
    { label: 'Funciones', href: '/funciones', match: '/funciones', glyph: 'calendar' },
    { label: 'Salas', href: '/salas', match: '/salas', glyph: 'chair' },
    { label: 'Departamentos', href: '/departamentos', match: '/departamentos', glyph: 'building' },
    { label: 'Ciudades', href: '/ciudades', match: '/ciudades', glyph: 'pin' },
    { label: 'Sucursales', href: '/sucursales', match: '/sucursales', glyph: 'store' },
    { label: 'Trabajadores', href: '/trabajadores', match: '/trabajadores', glyph: 'users' },
    { label: 'Configuración', href: '/profile', match: '/profile', glyph: 'settings' },
];

const titles = {
    '/': 'Inicio',
    '/dashboard': 'Inicio',
    '/peliculas': 'Películas',
    '/funciones': 'Funciones',
    '/salas': 'Salas',
    '/sucursales': 'Sucursales',
    '/departamentos': 'Departamentos',
    '/ciudades': 'Ciudades',
    '/trabajadores': 'Trabajadores',
    '/profile': 'Mi perfil',
};

function MenuIcon({ glyph }) {
    const icons = {
        home: <><path d="m3 10 9-7 9 7" /><path d="M5 9v12h14V9M9 21v-7h6v7" /></>,
        film: <><path d="M3 7h18v14H3zM3 7l2-4 16 4M8 7l2-4m3 4 2-4m3 4 2-4M7 11v2m5-2v2m5-2v2" /></>,
        calendar: <><rect x="3" y="5" width="18" height="16" rx="2" /><path d="M16 3v4M8 3v4M3 11h18" /></>,
        chair: <><path d="M7 10V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v5M5 10h14v5a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-5ZM7 17v4m10-4v4" /></>,
        building: <><path d="M3 21h18M5 21V5l7-3 7 3v16M9 9h1m4 0h1m-6 4h1m4 0h1m-6 4h1m4 0h1" /></>,
        store: <><path d="M3 10h18l-1.5-6h-15L3 10ZM5 10v10h14V10M9 20v-6h6v6M3 10a3 3 0 0 0 6 0 3 3 0 0 0 6 0 3 3 0 0 0 6 0" /></>,
        pin: <><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z" /><circle cx="12" cy="10" r="2.5" /></>,
        users: <><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" /><circle cx="10" cy="7" r="4" /><path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" /></>,
        settings: <><circle cx="12" cy="12" r="3" /><path d="m19.4 15 .1.1 1.4 1.1-1.4 2.4-1.7-.7a8 8 0 0 1-1.6.9l-.3 1.8h-2.8l-.3-1.8a8 8 0 0 1-1.6-.9l-1.7.7-1.4-2.4 1.4-1.1a7 7 0 0 1 0-1.9l-1.4-1.1 1.4-2.4 1.7.7a8 8 0 0 1 1.6-.9l.3-1.8h2.8l.3 1.8a8 8 0 0 1 1.6.9l1.7-.7 1.4 2.4-1.4 1.1a7 7 0 0 1 0 1.9Z" /></>,
    };

    return (
        <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/[.06] text-slate-300">
            <svg viewBox="0 0 24 24" className="h-[17px] w-[17px]" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                {icons[glyph]}
            </svg>
        </span>
    );
}

export default function AuthenticatedLayout({ header, children }) {
    const page = usePage();
    const { auth, flash } = page.props;
    const currentUrl = page.url;
    const path = currentUrl.split('?')[0];
    const title = page.props.title || Object.entries(titles)
        .find(([prefix]) => path === prefix || path.startsWith(`${prefix}/`))?.[1] || 'CINE';
    const description = usePage().props.description;
    const [collapsed, setCollapsed] = useState(false);
    const [mobileOpen, setMobileOpen] = useState(false);
    const [mobileSearchOpen, setMobileSearchOpen] = useState(false);

    return (
        <div className="min-h-screen bg-[#08090D] text-slate-100">
            {mobileOpen && (
                <button
                    type="button"
                    aria-label="Cerrar menú"
                    className="fixed inset-0 z-40 bg-black/70 backdrop-blur-sm lg:hidden"
                    onClick={() => setMobileOpen(false)}
                />
            )}

            <aside className={`fixed inset-y-0 left-0 z-50 flex flex-col border-r border-white/[.06] bg-[#0D0F16] text-white shadow-2xl shadow-black/30 transition-all duration-300 lg:translate-x-0 ${
                mobileOpen ? 'translate-x-0' : '-translate-x-full'
            } ${collapsed ? 'w-[76px]' : 'w-[248px]'}`}>
                <div className={`flex h-[76px] items-center border-b border-white/[.08] ${collapsed ? 'justify-center px-3' : 'gap-3 px-6'}`}>
                    <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 text-lg font-black shadow-lg shadow-indigo-950/50">C</span>
                    {!collapsed && (
                        <div>
                            <p className="text-sm font-extrabold tracking-[.2em]">CINE</p>
                            <p className="mt-0.5 text-[10px] uppercase tracking-[.18em] text-slate-500">Cine · Gestión</p>
                        </div>
                    )}
                </div>

                <nav aria-label="Menú principal" className="flex-1 space-y-1 overflow-y-auto px-3 py-5">
                    <p className={`mb-3 px-3 text-[10px] font-bold uppercase tracking-[.18em] text-slate-600 ${collapsed ? 'sr-only' : ''}`}>General</p>
                    {navigation.map((item) => {
                        const active = (item.label === 'Inicio' && path === '/') || (item.href.includes('#')
                            ? currentUrl.includes(item.href.slice(item.href.indexOf('#')))
                            : path === item.match || path.startsWith(`${item.match}/`));

                        return (
                            <Link
                                key={item.label}
                                href={item.href}
                                title={collapsed ? item.label : undefined}
                                onClick={() => setMobileOpen(false)}
                                className={`group flex items-center gap-3 rounded-xl px-2.5 py-2 text-sm transition ${
                                    active
                                        ? 'bg-gradient-to-r from-violet-600 to-indigo-600 font-semibold text-white shadow-md shadow-indigo-950/40'
                                        : 'text-slate-400 hover:bg-white/[.06] hover:text-white'
                                }`}
                            >
                                <MenuIcon glyph={item.glyph} />
                                {!collapsed && <span className="truncate">{item.label}</span>}
                                {!collapsed && active && <span className="ml-auto h-1.5 w-1.5 rounded-full bg-white" />}
                            </Link>
                        );
                    })}
                </nav>

                {!collapsed && (
                    <div className="m-3 rounded-xl border border-white/[.08] bg-white/[.03] p-3">
                        <p className="text-xs font-semibold text-slate-200">Panel de gestión</p>
                        <p className="mt-1 text-[11px] leading-5 text-slate-500">Cada historia comienza detrás de escena.</p>
                    </div>
                )}
            </aside>

            <div className={`min-h-screen transition-[padding] duration-300 ${collapsed ? 'lg:pl-[76px]' : 'lg:pl-[248px]'}`}>
                <header className="sticky top-0 z-30 border-b border-white/[.06] bg-[#08090D]/85 backdrop-blur-xl">
                    <div className="flex h-[76px] items-center justify-between gap-4 px-4 sm:px-7 lg:px-9">
                        <div className="flex min-w-0 items-center gap-3">
                            <button
                                type="button"
                                aria-label="Abrir menú"
                                className="rounded-lg border border-white/10 p-2 text-slate-300 transition hover:bg-white/[.06] lg:hidden"
                                onClick={() => setMobileOpen(true)}
                            >
                                <svg viewBox="0 0 24 24" className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="1.8" aria-hidden="true">
                                    <path d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <button
                                type="button"
                                aria-label={collapsed ? 'Expandir menú' : 'Contraer menú'}
                                className="hidden rounded-lg border border-white/10 p-2 text-slate-300 transition hover:bg-white/[.06] lg:block"
                                onClick={() => setCollapsed((value) => !value)}
                            >
                                <svg viewBox="0 0 24 24" className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="1.8" aria-hidden="true">
                                    <path d="M4 5h16v14H4zM9 5v14" />
                                </svg>
                            </button>
                            <div className="min-w-0">
                                <p className="truncate text-[10px] font-semibold uppercase tracking-[.17em] text-slate-500">CINE / Gestión</p>
                                <h1 className="truncate text-lg font-bold text-white sm:text-xl">{title}</h1>
                                {description && <p className="hidden max-w-xl truncate text-xs text-slate-400 xl:block">{description}</p>}
                            </div>
                        </div>

                        <form method="get" action="/buscar" role="search" className="hidden max-w-sm flex-1 items-center rounded-xl border border-white/[.09] bg-white/[.03] px-3 transition focus-within:border-violet-400/50 focus-within:bg-white/[.05] md:flex">
                                <svg viewBox="0 0 24 24" className="h-4 w-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" strokeWidth="1.8" aria-hidden="true">
                                    <circle cx="11" cy="11" r="7" /><path d="m20 20-4-4" />
                                </svg>
                                <input name="search" type="search" placeholder="Buscar en CINE..." aria-label="Buscar en CINE" className="w-full border-0 bg-transparent px-2.5 py-2 text-xs text-slate-100 outline-none ring-0 placeholder:text-slate-500 focus:ring-0" />
                        </form>

                        <div className="flex shrink-0 items-center gap-3 sm:gap-5">
                            <button
                                type="button"
                                aria-label={mobileSearchOpen ? 'Cerrar búsqueda' : 'Abrir búsqueda'}
                                aria-expanded={mobileSearchOpen}
                                className="rounded-lg border border-white/10 p-2 text-slate-300 transition hover:bg-white/[.06] md:hidden"
                                onClick={() => setMobileSearchOpen((open) => !open)}
                            >
                                <svg viewBox="0 0 24 24" className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="1.8" aria-hidden="true">
                                    <circle cx="11" cy="11" r="7" /><path d="m20 20-4-4" />
                                </svg>
                            </button>
                            <div className="hidden text-right sm:block">
                                <p className="text-xs font-semibold text-slate-200">Bienvenido a CINE</p>
                                <p className="text-[11px] text-slate-500">{new Date().toLocaleDateString('es-CO', { dateStyle: 'medium' })}</p>
                            </div>
                            <Link href="/funciones" aria-label="Programación de funciones" title="Programación de funciones" className="relative rounded-lg p-2 text-slate-400 transition hover:bg-white/[.06] hover:text-white">
                                <svg viewBox="0 0 24 24" className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="1.7" aria-hidden="true">
                                    <rect x="3" y="5" width="18" height="16" rx="2" />
                                    <path d="M16 3v4M8 3v4M3 11h18" />
                                </svg>
                            </Link>
                            <div className="hidden h-8 w-px bg-white/10 sm:block" />
                            <div className="flex items-center gap-2">
                                <span className="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-violet-500 to-indigo-600 text-xs font-bold uppercase text-white shadow-lg shadow-indigo-950/40">
                                    {(auth?.user?.name || 'U').slice(0, 1)}
                                </span>
                                <div className="hidden md:block">
                                    <p className="max-w-32 truncate text-xs font-semibold text-slate-200">{auth?.user?.name || 'Invitado'}</p>
                                    <p className="text-[10px] text-slate-500">{auth?.user ? 'Administrador' : 'Explorando CINE'}</p>
                                </div>
                                <div className="group relative">
                                    <button type="button" aria-label="Opciones de usuario" className="rounded p-1 text-slate-400 transition hover:bg-white/[.06] hover:text-white">
                                        <svg viewBox="0 0 20 20" className="h-4 w-4" fill="currentColor" aria-hidden="true"><path fillRule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.09 1.04l-4.25 4.5a.75.75 0 0 1-1.09 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clipRule="evenodd" /></svg>
                                    </button>
                                    <div className="invisible absolute right-0 top-full z-50 mt-2 w-44 rounded-xl border border-white/10 bg-[#151923] p-1.5 opacity-0 shadow-xl shadow-black/40 transition group-focus-within:visible group-focus-within:opacity-100 group-hover:visible group-hover:opacity-100">
                                        {auth?.user ? (
                                            <>
                                                <Link href="/profile" className="block rounded-lg px-3 py-2 text-xs text-slate-200 transition hover:bg-white/[.06]">Mi perfil</Link>
                                                <Link href="/logout" method="post" as="button" className="block w-full rounded-lg px-3 py-2 text-left text-xs text-rose-300 transition hover:bg-rose-500/10">Cerrar sesión</Link>
                                            </>
                                        ) : <Link href="/login" className="block rounded-lg px-3 py-2 text-xs text-slate-200 transition hover:bg-white/[.06]">Iniciar sesión</Link>}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {mobileSearchOpen && (
                        <form method="get" action="/buscar" role="search" className="border-t border-white/[.06] px-4 py-3 md:hidden">
                            <div className="flex items-center rounded-xl border border-white/[.09] bg-white/[.03] px-3 focus-within:border-violet-400/50">
                                <svg viewBox="0 0 24 24" className="h-4 w-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" strokeWidth="1.8" aria-hidden="true">
                                    <circle cx="11" cy="11" r="7" /><path d="m20 20-4-4" />
                                </svg>
                                <input name="search" type="search" placeholder="Buscar en CINE..." aria-label="Buscar en CINE" autoFocus className="w-full border-0 bg-transparent px-2.5 py-2 text-sm text-slate-100 outline-none ring-0 placeholder:text-slate-500 focus:ring-0" />
                            </div>
                        </form>
                    )}
                </header>

                <main className="mx-auto max-w-[1600px] px-4 py-6 sm:px-7 sm:py-8 lg:px-9">
                    {flash?.success && <div role="status" className="mb-5 rounded-xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">{flash.success}</div>}
                    {flash?.error && <div role="alert" className="mb-5 rounded-xl border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-sm text-rose-200">{flash.error}</div>}
                    {header && <div className="sr-only">{header}</div>}
                    {children}
                </main>
                <Footer />
            </div>
        </div>
    );
}
