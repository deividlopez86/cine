import { Head } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import MovieForm from '../../Components/MovieForm';

export default function Create({ generos, clasificaciones }: { generos: string[]; clasificaciones: string[] }) {
    return (
        <AuthenticatedLayout>
            <Head title="Agregar película" />
            <main className="mx-auto max-w-4xl px-5 py-10 sm:px-10 sm:py-14">
                <p className="text-[10px] font-bold uppercase tracking-[.24em] text-cyan-300">Administración · Catálogo</p>
                <h1 className="mt-2 text-3xl font-bold tracking-tight">Agregar película</h1>
                <p className="mt-2 text-sm text-slate-400">Completa los datos para agregar una nueva historia al catálogo.</p>
                <section className="mt-8 rounded-2xl border border-white/[.08] bg-white/[.025] p-5 sm:p-8">
                    <MovieForm generos={generos} clasificaciones={clasificaciones} />
                </section>
            </main>
        </AuthenticatedLayout>
    );
}
