import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Index({ search, results }) {
    return (
        <AuthenticatedLayout>
            <Head title="Búsqueda" />
            <section className="mx-auto max-w-4xl">
                <p className="text-[10px] font-bold uppercase tracking-[.22em] text-violet-300">Explora CINE</p>
                <h2 className="mt-2 text-2xl font-semibold tracking-tight text-white">Resultados de búsqueda</h2>
                <p className="mt-2 text-sm text-slate-400">
                    {results.length
                        ? `${results.length} resultados para “${search}”`
                        : `No encontramos resultados para “${search}”.`}
                </p>

                {results.length > 0 && (
                    <div className="mt-6 grid gap-3 sm:grid-cols-2">
                        {results.map((result, index) => (
                            <Link
                                key={`${result.type}-${result.title}-${index}`}
                                href={result.href}
                                className="group rounded-2xl border border-white/[.08] bg-[#11141d] p-5 transition hover:-translate-y-0.5 hover:border-violet-400/30 hover:bg-[#151923]"
                            >
                                <span className="text-[10px] font-bold uppercase tracking-[.16em] text-violet-300">{result.type}</span>
                                <span className="mt-2 block font-semibold text-slate-100 transition group-hover:text-white">{result.title}</span>
                                <span className="mt-1 block text-xs text-slate-400">{result.detail || 'Registro de CINE'}</span>
                            </Link>
                        ))}
                    </div>
                )}
            </section>
        </AuthenticatedLayout>
    );
}
