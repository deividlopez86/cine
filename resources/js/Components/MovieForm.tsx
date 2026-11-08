import { useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import type { Movie } from './MovieCard';

type FormFields = {
    titulo: string;
    director: string;
    anio: string | number;
    genero: string;
    duracion_minutos: string | number;
    clasificacion: string;
    sinopsis: string;
    imagen: string;
};

type Props = {
    pelicula?: Movie;
    generos: string[];
    clasificaciones: string[];
};

const fieldClass = 'mt-2 w-full rounded-xl border border-white/10 bg-[#101116] px-4 py-3 text-sm text-white placeholder:text-slate-600 focus:border-cyan-300/50 focus:ring-cyan-300/20';

export default function MovieForm({ pelicula, generos, clasificaciones }: Props) {
    const form = useForm<FormFields>({
        titulo: pelicula?.titulo ?? '',
        director: pelicula?.director ?? '',
        anio: pelicula?.anio ?? '',
        genero: pelicula?.genero ?? '',
        duracion_minutos: pelicula?.duracion_minutos ?? '',
        clasificacion: pelicula?.clasificacion ?? '',
        sinopsis: pelicula?.sinopsis ?? '',
        imagen: pelicula?.imagen ?? '',
    });

    function submit(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();
        if (pelicula) {
            form.put(`/peliculas/${pelicula.id}`);
        } else {
            form.post('/peliculas');
        }
    }

    const slugPreview = form.data.titulo
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-|-$/g, '');

    return (
        <form onSubmit={submit} className="space-y-6">
            <div className="grid gap-5 sm:grid-cols-2">
                <label className="sm:col-span-2">
                    <span className="text-sm font-medium text-slate-200">Título <span className="text-cyan-300">*</span></span>
                    <input autoFocus required maxLength={150} value={form.data.titulo} onChange={(event) => form.setData('titulo', event.target.value)} className={fieldClass} placeholder="Ej. La última frontera" />
                    {form.errors.titulo && <span className="mt-1 block text-xs text-rose-300">{form.errors.titulo}</span>}
                    <span className="mt-2 block text-xs text-slate-500">Slug: <span className="text-cyan-200">{slugPreview || 'se-genera-desde-el-titulo'}</span></span>
                </label>

                <label>
                    <span className="text-sm font-medium text-slate-200">Director</span>
                    <input maxLength={120} value={form.data.director} onChange={(event) => form.setData('director', event.target.value)} className={fieldClass} placeholder="Nombre del director" />
                    {form.errors.director && <span className="mt-1 block text-xs text-rose-300">{form.errors.director}</span>}
                </label>
                <label>
                    <span className="text-sm font-medium text-slate-200">Año de estreno</span>
                    <input type="number" min="1888" max="2100" value={form.data.anio} onChange={(event) => form.setData('anio', event.target.value)} className={fieldClass} placeholder="2026" />
                    {form.errors.anio && <span className="mt-1 block text-xs text-rose-300">{form.errors.anio}</span>}
                </label>
                <label>
                    <span className="text-sm font-medium text-slate-200">Género <span className="text-cyan-300">*</span></span>
                    <select required value={form.data.genero} onChange={(event) => form.setData('genero', event.target.value)} className={fieldClass}>
                        <option value="">Selecciona un género</option>
                        {Array.from(new Set([...generos, ...(form.data.genero ? [form.data.genero] : [])])).map((genero) => <option key={genero} value={genero}>{genero}</option>)}
                    </select>
                    {form.errors.genero && <span className="mt-1 block text-xs text-rose-300">{form.errors.genero}</span>}
                </label>
                <label>
                    <span className="text-sm font-medium text-slate-200">Duración (minutos) <span className="text-cyan-300">*</span></span>
                    <input required type="number" min="1" max="1000" value={form.data.duracion_minutos} onChange={(event) => form.setData('duracion_minutos', event.target.value)} className={fieldClass} placeholder="120" />
                    {form.errors.duracion_minutos && <span className="mt-1 block text-xs text-rose-300">{form.errors.duracion_minutos}</span>}
                </label>
                <label>
                    <span className="text-sm font-medium text-slate-200">Clasificación <span className="text-cyan-300">*</span></span>
                    <select required value={form.data.clasificacion} onChange={(event) => form.setData('clasificacion', event.target.value)} className={fieldClass}>
                        <option value="">Selecciona una clasificación</option>
                        {clasificaciones.map((rating) => <option key={rating} value={rating}>{rating}</option>)}
                    </select>
                    {form.errors.clasificacion && <span className="mt-1 block text-xs text-rose-300">{form.errors.clasificacion}</span>}
                </label>
                <label className="sm:col-span-2">
                    <span className="text-sm font-medium text-slate-200">URL del póster</span>
                    <input type="url" maxLength={2048} value={form.data.imagen} onChange={(event) => form.setData('imagen', event.target.value)} className={fieldClass} placeholder="https://..." />
                    {form.errors.imagen && <span className="mt-1 block text-xs text-rose-300">{form.errors.imagen}</span>}
                </label>
                <label className="sm:col-span-2">
                    <span className="text-sm font-medium text-slate-200">Sinopsis <span className="text-cyan-300">*</span></span>
                    <textarea required maxLength={5000} rows={5} value={form.data.sinopsis} onChange={(event) => form.setData('sinopsis', event.target.value)} className={fieldClass} placeholder="Cuéntanos de qué trata la película..." />
                    {form.errors.sinopsis && <span className="mt-1 block text-xs text-rose-300">{form.errors.sinopsis}</span>}
                </label>
            </div>

            {Object.keys(form.errors).length > 0 && <p role="alert" className="rounded-xl border border-rose-400/20 bg-rose-400/10 px-4 py-3 text-sm text-rose-200">Revisa los campos marcados antes de continuar.</p>}
            <div className="flex flex-wrap gap-3 border-t border-white/[.07] pt-5">
                <button type="submit" disabled={form.processing} className="rounded-full bg-gradient-to-r from-violet-600 to-cyan-500 px-6 py-3 text-sm font-bold text-white transition hover:brightness-110 disabled:cursor-wait disabled:opacity-60">
                    {form.processing ? 'Guardando...' : pelicula ? 'Guardar cambios' : 'Agregar película'}
                </button>
                <a href="/peliculas" className="rounded-full border border-white/10 px-6 py-3 text-sm font-semibold text-slate-300 transition hover:bg-white/[.06]">Cancelar</a>
            </div>
        </form>
    );
}
