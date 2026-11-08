import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import ConfirmDeleteDialog from '@/Components/ConfirmDeleteDialog';
import { Head, Link } from '@inertiajs/react';

function readValue(record, key) {
    if (key === 'nombre_completo') {
        return `${record.nombre || ''} ${record.apellido || ''}`.trim();
    }

    return key.split('.').reduce((value, part) => value?.[part], record) ?? '—';
}

function cellValue(record, key) {
    const value = readValue(record, key);

    if (key === 'estado' && value !== '—') {
        return (
            <span className={`inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ${
                value === 'activo' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600'
            }`}>
                {value === 'activo' ? 'Activo' : 'Inactivo'}
            </span>
        );
    }

    return value;
}

export default function Index({ title, description, resource, createUrl, records, columns, search = '', filters = {} }) {
    const stateFilter = ['sucursales', 'trabajadores', 'salas', 'funciones'].includes(resource);
    const hasCityFilter = resource === 'ciudades';
    const hasBranchFilter = resource === 'trabajadores';

    return (
        <AuthenticatedLayout>
            <Head title={title} />
            <section className="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <p className="text-sm text-slate-500">{description}</p>
                    <p className="mt-2 text-xs font-medium text-slate-400">{records.total} registros</p>
                </div>
                <div className="flex flex-col gap-2 sm:flex-row">
                    <form method="get" action={`/${resource}`} className="flex flex-wrap items-center gap-2 rounded-xl border border-white/[.08] bg-[#11141d] p-1.5 shadow-sm shadow-black/20">
                        <input
                            type="search"
                            name="search"
                            defaultValue={search}
                            placeholder={`Buscar ${title.toLowerCase()}...`}
                            className="w-full border-0 bg-transparent px-3 py-2 text-sm text-slate-100 outline-none ring-0 placeholder:text-slate-500 focus:border-0 focus:ring-0 sm:w-48"
                        />
                        {stateFilter && (
                            <select name="estado" defaultValue={filters.estado || ''} aria-label="Filtrar por estado" className="rounded-lg border-0 bg-[#1a1e29] py-2 pl-3 pr-7 text-xs font-medium text-slate-200 focus:ring-violet-500">
                                <option value="">Todos los estados</option>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        )}
                        {hasCityFilter && (
                            <select name="departamento" defaultValue={filters.departamento || ''} aria-label="Filtrar por departamento" className="rounded-lg border-0 bg-[#1a1e29] py-2 pl-3 pr-7 text-xs font-medium text-slate-200 focus:ring-violet-500">
                                <option value="">Todos los departamentos</option>
                                {(filters.departamentos || []).map((department) => <option key={department.id} value={department.id}>{department.nombre}</option>)}
                            </select>
                        )}
                        {hasBranchFilter && (
                            <select name="sucursal" defaultValue={filters.sucursal || ''} aria-label="Filtrar por sucursal" className="rounded-lg border-0 bg-[#1a1e29] py-2 pl-3 pr-7 text-xs font-medium text-slate-200 focus:ring-violet-500">
                                <option value="">Todas las sucursales</option>
                                {(filters.sucursales || []).map((branch) => <option key={branch.id} value={branch.id}>{branch.nombre}</option>)}
                            </select>
                        )}
                        <button type="submit" className="rounded-lg bg-white/[.08] px-3 py-2 text-xs font-semibold text-slate-200 transition hover:bg-white/[.13]">Aplicar</button>
                    </form>
                    <Link href={createUrl} className="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-indigo-950/30 transition hover:brightness-110">
                        <span className="text-lg leading-none">+</span> Nuevo registro
                    </Link>
                </div>
            </section>

            <div className="overflow-hidden rounded-2xl border border-white/[.08] bg-[#11141d] shadow-xl shadow-black/20">
                <div className="overflow-x-auto">
                    <table className="min-w-full divide-y divide-white/[.06]">
                        <thead className="bg-white/[.03]">
                            <tr>
                                {columns.map((column) => <th key={column.key} scope="col" className="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">{column.label}</th>)}
                                <th scope="col" className="px-5 py-3.5 text-right text-[11px] font-bold uppercase tracking-wider text-slate-400">Acciones</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-white/[.06]">
                            {records.data.length ? records.data.map((record) => (
                                <tr key={record.id} className="transition hover:bg-white/[.025]">
                                    {columns.map((column) => <td key={column.key} className="whitespace-nowrap px-5 py-4 text-sm text-slate-300">{cellValue(record, column.key)}</td>)}
                                    <td className="whitespace-nowrap px-5 py-4 text-right text-sm">
                                        <div className="inline-flex items-center gap-3">
                                            <Link href={`/${resource}/${record.id}`} className="font-semibold text-slate-400 transition hover:text-violet-300">Ver</Link>
                                            <Link href={`/${resource}/${record.id}/edit`} className="font-semibold text-slate-400 transition hover:text-violet-300">Editar</Link>
                                            <ConfirmDeleteDialog href={`/${resource}/${record.id}`} label={`este registro de ${title.toLowerCase()}`} />
                                        </div>
                                    </td>
                                </tr>
                            )) : (
                                <tr><td colSpan={columns.length + 1} className="px-5 py-12 text-center text-sm text-slate-500">No hay registros para mostrar.</td></tr>
                            )}
                        </tbody>
                    </table>
                </div>
                {records.links?.length > 3 && (
                    <div className="flex flex-wrap justify-end gap-1 border-t border-white/[.06] px-4 py-3">
                        {records.links.map((link, index) => link.url ? (
                            <Link
                                key={`${link.label}-${index}`}
                                href={link.url}
                                preserveScroll
                                className={`rounded-lg px-3 py-1.5 text-xs ${
                                    link.active ? 'bg-violet-600 font-bold text-white' : 'text-slate-400 hover:bg-white/[.06]'
                                }`}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ) : (
                            <span key={`${link.label}-${index}`} className="rounded-lg px-3 py-1.5 text-xs text-slate-600" dangerouslySetInnerHTML={{ __html: link.label }} />
                        ))}
                    </div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
