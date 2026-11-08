import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';

export default function FormInput({ field, value, error, onChange }) {
    return (
        <div className={field.type === 'textarea' ? 'sm:col-span-2' : ''}>
            <InputLabel htmlFor={field.name} value={field.label} className="!text-slate-300" />
            {field.type === 'textarea' ? (
                <textarea
                    id={field.name}
                    name={field.name}
                    value={value ?? ''}
                    required={field.required}
                    min={field.min}
                    max={field.max}
                    step={field.step}
                    onChange={onChange}
                    rows={4}
                    className="mt-1 block w-full rounded-lg border-white/10 bg-[#0D0F16] text-sm text-slate-100 shadow-sm focus:border-violet-500 focus:ring-violet-500"
                />
            ) : (
                <TextInput
                    id={field.name}
                    name={field.name}
                    type={field.type || 'text'}
                    value={value ?? ''}
                    required={field.required}
                    onChange={onChange}
                    className="mt-1 block w-full border-white/10 bg-[#0D0F16] text-slate-100 focus:border-violet-500 focus:ring-violet-500"
                    autoComplete="off"
                />
            )}
            <InputError message={error} className="mt-2" />
        </div>
    );
}
