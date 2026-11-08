import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Show({ title, records, editUrl, resource }) {
    return (
        <AuthenticatedLayout>
            <Head title={title} />
            <section className="mx-auto max-w-3xl overflow-hidden rounded-2xl border border-white/[.08] bg-[#11141d] shadow-xl shadow-black/20">
                <div className="border-b border-white/[.08] px-6 py-5">
                    <p className="text-sm text-slate-500">Consulta los datos y las relaciones de este registro.</p>
                </div>
                <dl className="grid gap-px bg-white/[.06] sm:grid-cols-2">
                    {records.map((record) => (
                        <div key={record.label} className="bg-[#11141d] px-6 py-4">
                            <dt className="text-xs font-semibold uppercase tracking-wide text-slate-400">{record.label}</dt>
                            <dd className="mt-1 break-words text-sm font-medium text-slate-200">{record.value || '—'}</dd>
                        </div>
                    ))}
                </dl>
                <div className="flex justify-end gap-3 border-t border-white/[.08] px-6 py-4">
                    <Link href={`/${resource}`} className="rounded-lg border border-white/10 px-4 py-2 text-sm font-semibold text-slate-300 transition hover:bg-white/[.05]">Volver</Link>
                    <Link href={editUrl} className="rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-violet-500">Editar</Link>
                </div>
            </section>
        </AuthenticatedLayout>
    );
}
