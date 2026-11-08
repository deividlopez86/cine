import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';

export default function FormSelect({ field, value, error, onChange }) {
    return (
        <div>
            <InputLabel htmlFor={field.name} value={field.label} className="!text-slate-300" />
            <select
                id={field.name}
                name={field.name}
                value={value ?? ''}
                required={field.required}
                onChange={onChange}
                className="mt-1 block w-full rounded-lg border-white/10 bg-[#0D0F16] text-sm text-slate-100 shadow-sm focus:border-violet-500 focus:ring-violet-500"
            >
                <option value="">Selecciona una opción</option>
                {(field.options || []).map((option) => <option key={option.value} value={option.value}>{option.label}</option>)}
            </select>
            <InputError message={error} className="mt-2" />
        </div>
    );
}
