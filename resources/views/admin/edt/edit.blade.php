@extends('layouts.app')
@section('title', 'Modifier une Séance')
@section('header-title', 'Modifier une Séance')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('admin.edt.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 transition">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour à l'emploi du temps
            </a>
        </div>

        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-800">Modifier la Séance</h3>
            </div>

            <form action="{{ route('admin.edt.update', $seance) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                @if($errors->any())
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                        <ul class="list-disc pl-5 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="module_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Module <span class="text-rose-500">*</span></label>
                        <select name="module_id" id="module_id" required class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 bg-white focus:outline-none focus:border-primary-400">
                            @foreach($modules as $module)
                                <option value="{{ $module->id }}" {{ old('module_id', $seance->module_id) == $module->id ? 'selected' : '' }}>{{ $module->nom }} ({{ $module->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="groupe_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Groupe <span class="text-rose-500">*</span></label>
                        <select name="groupe_id" id="groupe_id" required class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 bg-white focus:outline-none focus:border-primary-400">
                            @foreach($groupes as $groupe)
                                <option value="{{ $groupe->id }}" {{ old('groupe_id', $seance->groupe_id) == $groupe->id ? 'selected' : '' }}>{{ $groupe->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="professeur_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Professeur <span class="text-rose-500">*</span></label>
                        <select name="professeur_id" id="professeur_id" required class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 bg-white focus:outline-none focus:border-primary-400">
                            @foreach($professeurs as $prof)
                                <option value="{{ $prof->id }}" {{ old('professeur_id', $seance->professeur_id) == $prof->id ? 'selected' : '' }}>{{ $prof->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="salle_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Salle <span class="text-rose-500">*</span></label>
                        <select name="salle_id" id="salle_id" required class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 bg-white focus:outline-none focus:border-primary-400">
                            @foreach($salles as $salle)
                                <option value="{{ $salle->id }}" {{ old('salle_id', $seance->salle_id) == $salle->id ? 'selected' : '' }}>{{ $salle->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date" class="block text-sm font-semibold text-slate-700 mb-1.5">Date <span class="text-rose-500">*</span></label>
                            <input type="date" name="date" id="date" value="{{ old('date', $seance->date->format('Y-m-d')) }}" required class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400">
                        </div>
                        <div>
                            <label for="type" class="block text-sm font-semibold text-slate-700 mb-1.5">Type <span class="text-rose-500">*</span></label>
                            <select name="type" id="type" required class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 bg-white focus:outline-none focus:border-primary-400">
                                @foreach(['Cours', 'TD', 'TP'] as $t)
                                    <option value="{{ $t }}" {{ old('type', $seance->type) == $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="heure_debut" class="block text-sm font-semibold text-slate-700 mb-1.5">Heure début <span class="text-rose-500">*</span></label>
                            <input type="time" name="heure_debut" id="heure_debut" value="{{ old('heure_debut', substr($seance->heure_debut, 0, 5)) }}" required class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400">
                        </div>
                        <div>
                            <label for="heure_fin" class="block text-sm font-semibold text-slate-700 mb-1.5">Heure fin <span class="text-rose-500">*</span></label>
                            <input type="time" name="heure_fin" id="heure_fin" value="{{ old('heure_fin', substr($seance->heure_fin, 0, 5)) }}" required class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400">
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.edt.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">Annuler</a>
                    <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow transition">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
