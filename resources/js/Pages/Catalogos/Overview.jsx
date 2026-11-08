import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

function readValue(record, key) {
    return key.split('.').reduce((value, part) => value?.[part], record) ?? '—';
}

export default function Overview({ title, description, columns, records }) {
    const rows = records.data || records;

    return (
        <AuthenticatedLayout>
            <Head title={title} />
            <p className="mb-6 text-sm text-slate-500">{description}</p>
            <div className="overflow-hidden rounded-2xl border border-white/[.08] bg-[#11141d] shadow-xl shadow-black/20">
                <div className="overflow-x-auto">
                    <table className="min-w-full divide-y divide-white/[.06]">
                        <thead className="bg-white/[.03]">
                            <tr>{columns.map((column) => <th key={column.key} className="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">{column.label}</th>)}</tr>
                        </thead>
                        <tbody className="divide-y divide-white/[.06]">
                            {rows.length ? rows.map((record) => (
                                <tr key={record.id} className="transition hover:bg-white/[.025]">
                                    {columns.map((column) => <td key={column.key} className="px-5 py-4 text-sm text-slate-300">{readValue(record, column.key)}</td>)}
                                </tr>
                            )) : <tr><td colSpan={columns.length} className="px-5 py-12 text-center text-sm text-slate-500">No hay registros para mostrar.</td></tr>}
                        </tbody>
                    </table>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
