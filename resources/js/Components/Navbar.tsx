import { Link } from '@inertiajs/react';

type Props = {
    auth?: { user?: { name: string } | null };
    active?: string;
};

export default function Navbar({ auth, active = '' }: Props) {
    const links = [
        { label: 'Inicio', href: '/', key: 'inicio' },
        { label: 'Películas', href: '/peliculas', key: 'peliculas' },
        { label: 'Series', href: '/#series', key: 'series' },
        { label: 'Tendencias', href: '/#tendencias', key: 'tendencias' },
        { label: 'Mi lista', href: '/#mi-lista', key: 'mi-lista' },
    ];

    return (
        <header className="sticky top-0 z-40 border-b border-white/[.07] bg-[#090a0d]/90 backdrop-blur-xl">
            <div className="mx-auto flex max-w-[1440px] items-center justify-between gap-5 px-5 py-4 lg:px-10">
                <Link href="/" className="flex shrink-0 items-center gap-2.5" aria-label="CinéPulse, inicio">
                    <span className="flex h-9 w-9 items-center justify-center rounded-xl border border-cyan-300/30 bg-gradient-to-br from-violet-600/30 to-cyan-400/20 text-cyan-200">
                        <svg viewBox="0 0 24 24" fill="none" className="h-5 w-5" aria-hidden="true">
                            <path d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5v-9Z" stroke="currentColor" strokeWidth="1.5" />
                            <path d="m10 8 6 4-6 4V8Z" fill="currentColor" />
                        </svg>
                    </span>
                    <span className="text-sm font-black tracking-[.13em] text-white">CINÉ<span className="text-cyan-300">PULSE</span></span>
                </Link>

                <nav aria-label="Navegación principal" className="hidden items-center gap-1 md:flex">
                    {links.map((link) => (
                        <a
                            key={link.key}
                            href={link.href}
                            className={`rounded-full px-3 py-2 text-xs font-medium transition ${
                                active === link.key
                                    ? 'bg-violet-600/20 text-cyan-200'
                                    : 'text-slate-400 hover:bg-white/[.06] hover:text-white'
                            }`}
                        >
                            {link.label}
                        </a>
                    ))}
                </nav>

                <div className="flex items-center gap-2">
                    <form action="/peliculas" method="get" className="hidden items-center rounded-full border border-white/10 bg-white/[.04] px-3 sm:flex">
                        <svg viewBox="0 0 24 24" fill="none" className="h-4 w-4 text-slate-500" aria-hidden="true">
                            <circle cx="11" cy="11" r="7" stroke="currentColor" strokeWidth="1.7" />
                            <path d="m16 16 4 4" stroke="currentColor" strokeWidth="1.7" />
                        </svg>
                        <input
                            aria-label="Buscar películas"
                            name="search"
                            placeholder="Buscar películas..."
                            className="w-36 border-0 bg-transparent py-2 pl-2 text-xs text-white placeholder:text-slate-500 focus:ring-0"
                        />
                    </form>
                    {auth?.user ? (
                        <Link href="/peliculas" className="rounded-full border border-white/10 px-4 py-2 text-xs font-semibold text-slate-200 transition hover:border-cyan-300/40 hover:text-cyan-200">
                            Administración
                        </Link>
                    ) : (
                        <Link href="/login" className="rounded-full bg-gradient-to-r from-violet-600 to-cyan-500 px-4 py-2 text-xs font-bold text-white transition hover:brightness-110">
                            Ingresar
                        </Link>
                    )}
                </div>
            </div>
            <nav aria-label="Navegación móvil" className="flex gap-2 overflow-x-auto px-5 pb-3 md:hidden">
                {links.map((link) => (
                    <a key={link.key} href={link.href} className="whitespace-nowrap rounded-full border border-white/[.08] px-3 py-1.5 text-xs text-slate-300">
                        {link.label}
                    </a>
                ))}
            </nav>
        </header>
    );
}
