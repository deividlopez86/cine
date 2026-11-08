import { useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function ConfirmDeleteDialog({ href, label }) {
    const [open, setOpen] = useState(false);
    const { delete: destroy, processing } = useForm();

    function confirmDelete() {
        destroy(href, {
            preserveScroll: true,
            onSuccess: () => setOpen(false),
        });
    }

    return (
        <>
            <button type="button" onClick={() => setOpen(true)} className="font-semibold text-rose-300 hover:text-rose-200">
                Eliminar
            </button>
            {open && (
                <div className="fixed inset-0 z-[70] flex items-center justify-center bg-black/75 p-4 backdrop-blur-sm" role="presentation">
                    <section role="dialog" aria-modal="true" aria-labelledby="delete-title" className="w-full max-w-md rounded-2xl border border-white/10 bg-[#151923] p-6 shadow-2xl shadow-black/50">
                        <h2 id="delete-title" className="text-lg font-bold text-white">Confirmar eliminación</h2>
                        <p className="mt-2 text-sm leading-6 text-slate-400">
                            ¿Deseas eliminar {label}? Esta acción no elimina registros relacionados.
                        </p>
                        <div className="mt-6 flex justify-end gap-3">
                            <button type="button" onClick={() => setOpen(false)} disabled={processing} className="rounded-lg border border-white/10 px-4 py-2 text-sm font-semibold text-slate-300 hover:bg-white/[.06]">
                                Cancelar
                            </button>
                            <button type="button" onClick={confirmDelete} disabled={processing} className="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-500 disabled:opacity-60">
                                {processing ? 'Eliminando…' : 'Sí, eliminar'}
                            </button>
                        </div>
                    </section>
                </div>
            )}
        </>
    );
}
