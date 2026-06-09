@extends('layouts.app')
@section('title', 'Saisie d\'une Note')
@section('header-title', 'Saisir une Note')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <div class="mb-5 flex items-center gap-2 text-sm text-slate-400">
            <a href="{{ route('admin.notes.index') }}" class="hover:text-primary-600 transition">Notes</a>
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-600 font-medium">Saisir une note</span>
        </div>

        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            {{-- Header --}}
            <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-violet-50">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-indigo-600 flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Saisir une nouvelle note</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Entrez les notes d'évaluation d'un étudiant pour un module</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('admin.notes.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

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

                {{-- Étudiant --}}
                <div>
                    <label for="etudiant_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Étudiant <span class="text-rose-500">*</span>
                    </label>
                    <select name="etudiant_id" id="etudiant_id" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 bg-white
                                   focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition">
                        <option value="">-- Choisir un étudiant --</option>
                        @foreach($etudiants as $etudiant)
                            <option value="{{ $etudiant->id }}" {{ old('etudiant_id') == $etudiant->id ? 'selected' : '' }}>
                                {{ $etudiant->user->name }} (Matricule : {{ $etudiant->matricule }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Module --}}
                <div>
                    <label for="module_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Module / Matière <span class="text-rose-500">*</span>
                    </label>
                    <select name="module_id" id="module_id" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 bg-white
                                   focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition">
                        <option value="">-- Choisir un module --</option>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}" {{ old('module_id') == $module->id ? 'selected' : '' }}>
                                {{ $module->nom }} ({{ $module->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- CC1 + CC2 + Examen --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="cc1" class="block text-sm font-semibold text-slate-700 mb-1.5">Note CC1</label>
                        <input type="number" step="0.25" min="0" max="20" name="cc1" id="cc1" value="{{ old('cc1') }}"
                               placeholder="Ex : 14.5"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800
                                      focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition">
                    </div>
                    <div>
                        <label for="cc2" class="block text-sm font-semibold text-slate-700 mb-1.5">Note CC2</label>
                        <input type="number" step="0.25" min="0" max="20" name="cc2" id="cc2" value="{{ old('cc2') }}"
                               placeholder="Ex : 12.0"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800
                                      focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition">
                    </div>
                    <div>
                        <label for="examen" class="block text-sm font-semibold text-slate-700 mb-1.5">Note Examen</label>
                        <input type="number" step="0.25" min="0" max="20" name="examen" id="examen" value="{{ old('examen') }}"
                               placeholder="Ex : 15.0"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800
                                      focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition">
                    </div>
                </div>

                {{-- Année universitaire --}}
                <div>
                    <label for="annee_universitaire" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Année Universitaire <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="annee_universitaire" id="annee_universitaire" value="{{ old('annee_universitaire', '2024-2025') }}" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800
                                  focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition">
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('admin.notes.index') }}"
                       class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow transition">
                        Créer l'entrée
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
