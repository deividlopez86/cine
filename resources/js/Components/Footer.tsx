import { Link } from '@inertiajs/react';

export default function Footer() {
    return (
        <footer className="border-t border-white/[.07] bg-[#090a0d]">
            <div className="mx-auto grid max-w-[1600px] gap-8 px-5 py-8 sm:grid-cols-2 sm:px-7 lg:grid-cols-[1.4fr_1fr_1fr] lg:px-9">
                <div>
                    <Link href="/" className="inline-flex items-center gap-2 text-sm font-black tracking-[.18em] text-white">
                        <span className="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-violet-500 to-indigo-600">C</span>
                        CINE
                    </Link>
                    <p className="mt-3 max-w-sm text-xs leading-5 text-slate-500">Tu espacio para administrar y descubrir historias de cine.</p>
                </div>
                <div>
                    <h2 className="text-xs font-bold uppercase tracking-[.16em] text-slate-300">Explorar</h2>
                    <div className="mt-4 grid gap-2 text-sm text-slate-500">
                        <Link href="/" className="transition hover:text-violet-200">Inicio</Link>
                        <Link href="/peliculas" className="transition hover:text-violet-200">Películas</Link>
                        <a href="/#generos" className="transition hover:text-violet-200">Géneros</a>
                    </div>
                </div>
                <div>
                    <h2 className="text-xs font-bold uppercase tracking-[.16em] text-slate-300">Información</h2>
                    <p className="mt-4 text-xs leading-6 text-slate-500">Acerca de CINE · Contacto · Privacidad · Términos y condiciones</p>
                    <p className="mt-2 text-xs text-slate-600">© {new Date().getFullYear()} CINE. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>
    );
}
