@extends('layouts.app')
@section('title', 'Déposer un Justificatif')
@section('header-title', 'Dépôt de Justificatif')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Justifier l'absence</h3>
                <p class="text-xs text-slate-500 mt-1">
                    Séance du {{ $absence->seance->date->format('d/m/Y') }} —
                    {{ $absence->seance->module->nom }} ({{ substr($absence->seance->heure_debut, 0, 5) }} - {{ substr($absence->seance->heure_fin, 0, 5) }})
                </p>
            </div>
            <form action="{{ route('etudiant.absences.justifier.store', $absence->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Fichier justificatif <span class="text-rose-500">*</span></label>
                    <input type="file" name="fichier" accept=".pdf,.png,.jpg,.jpeg" required
                        class="block w-full text-sm text-slate-600 border border-slate-200 rounded-xl px-3 py-2 bg-slate-50 focus:outline-none focus:border-primary-400 focus:ring-primary-400 cursor-pointer">
                    <p class="text-xs text-slate-400 mt-1">Formats acceptés : PDF, PNG, JPG. Taille maximale : 5 Mo.</p>
                    @error('fichier') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 text-xs text-amber-800">
                    <strong>Important :</strong> Le justificatif sera examiné par l'administration. Seuls les documents valides (certificats médicaux, convocations officielles…) seront acceptés.
                </div>
                <div class="flex space-x-3 justify-end">
                    <a href="{{ route('etudiant.absences.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 border border-slate-200 rounded-xl hover:bg-slate-50 transition">Annuler</a>
                    <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 transition">Soumettre le justificatif</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
