@extends('layouts.app')
@section('title', 'Modifier le Module')
@section('header-title', 'Modifier le Module')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <div class="mb-5 flex items-center gap-2 text-sm text-slate-400">
            <a href="{{ route('admin.modules.index') }}" class="hover:text-primary-600 transition">Modules</a>
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-600 font-medium">Modifier — {{ $module->nom }}</span>
        </div>

        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            {{-- Header --}}
            <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-violet-50">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                        {{ substr($module->code, 0, 2) }}
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Modifier le module</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Mettez à jour les informations et l'enseignant responsable</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('admin.modules.update', $module->id) }}" method="POST" class="p-6 space-y-5">
                @csrf @method('PUT')

                @if($errors->any())
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                        <div class="font-semibold mb-1">Veuillez corriger les erreurs suivantes :</div>
                        <ul class="list-disc pl-5 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Nom --}}
                <div>
                    <label for="nom" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nom du module <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nom" id="nom" value="{{ old('nom', $module->nom) }}" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition">
                </div>

                {{-- Code + Filière --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="code" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Code unique du module <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="code" id="code" value="{{ old('code', $module->code) }}" required
                               placeholder="Ex : OO-301"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400
                                      focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition uppercase">
                    </div>
                    <div>
                        <label for="filiere_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Filière rattachée <span class="text-rose-500">*</span>
                        </label>
                        <select name="filiere_id" id="filiere_id" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 bg-white
                                       focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition">
                            @foreach($filieres as $filiere)
                                <option value="{{ $filiere->id }}" {{ old('filiere_id', $module->filiere_id) == $filiere->id ? 'selected' : '' }}>
                                    {{ $filiere->nom }} ({{ $filiere->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Coeff + Semestre + Vol. Horaire --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="coefficient" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Coefficient <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" step="0.5" name="coefficient" id="coefficient" value="{{ old('coefficient', $module->coefficient) }}" required
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800
                                      focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition">
                    </div>
                    <div>
                        <label for="semestre" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Semestre <span class="text-rose-500">*</span>
                        </label>
                        <select name="semestre" id="semestre" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 bg-white
                                       focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition">
                            <option value="1" {{ old('semestre', $module->semestre) == '1' ? 'selected' : '' }}>Semestre 1</option>
                            <option value="2" {{ old('semestre', $module->semestre) == '2' ? 'selected' : '' }}>Semestre 2</option>
                        </select>
                    </div>
                    <div>
                        <label for="volume_horaire" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Volume horaire (heures) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="volume_horaire" id="volume_horaire" value="{{ old('volume_horaire', $module->volume_horaire) }}" required
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800
                                      focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition">
                    </div>
                </div>

                {{-- Enseignant affecté --}}
                <div>
                    <label for="professeur_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Enseignant responsable <span class="text-slate-400 font-normal">(facultatif)</span>
                    </label>
                    <select name="professeur_id" id="professeur_id"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 bg-white
                                   focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition">
                        <option value="">-- Aucun enseignant affecté --</option>
                        @foreach($professeurs as $professeur)
                            <option value="{{ $professeur->id }}" {{ old('professeur_id', $module->professeur_id) == $professeur->id ? 'selected' : '' }}>
                                {{ $professeur->user->name }} ({{ $professeur->specialite }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('admin.modules.index') }}"
                       class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow transition">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
