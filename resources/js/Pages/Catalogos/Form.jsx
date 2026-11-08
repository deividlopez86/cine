import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import FormInput from '@/Components/FormInput';
import FormSelect from '@/Components/FormSelect';
import PrimaryButton from '@/Components/PrimaryButton';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Form({ title, action, method, cancelUrl, fields, values }) {
    const { data, setData, [method]: submit, processing, errors } = useForm(values);

    function handleSubmit(event) {
        event.preventDefault();
        submit(action, { preserveScroll: true });
    }

    return (
        <AuthenticatedLayout>
            <Head title={title} />
            <div className="mx-auto max-w-3xl">
                <div className="mb-6">
                    <p className="text-sm text-slate-500">Completa la información del registro y guarda los cambios.</p>
                </div>
                <form onSubmit={handleSubmit} className="space-y-6 rounded-2xl border border-white/[.08] bg-[#11141d] p-5 shadow-xl shadow-black/20 sm:p-8">
                    <div className="grid gap-5 sm:grid-cols-2">
                        {fields.map((field) => (
                            field.type === 'select'
                                ? <FormSelect key={field.name} field={field} value={data[field.name]} error={errors[field.name]} onChange={(event) => setData(field.name, event.target.value)} />
                                : <FormInput key={field.name} field={field} value={data[field.name]} error={errors[field.name]} onChange={(event) => setData(field.name, event.target.value)} />
                        ))}
                    </div>
                    <div className="flex flex-col-reverse justify-end gap-3 border-t border-white/[.08] pt-5 sm:flex-row">
                        <Link href={cancelUrl} className="inline-flex items-center justify-center rounded-lg border border-white/10 px-4 py-2 text-sm font-semibold text-slate-300 transition hover:bg-white/[.05]">Cancelar</Link>
                        <PrimaryButton disabled={processing} className="justify-center bg-violet-600 hover:bg-violet-500 focus:bg-violet-500 active:bg-violet-700">
                            {processing ? 'Guardando...' : 'Guardar'}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </AuthenticatedLayout>
    );
}
