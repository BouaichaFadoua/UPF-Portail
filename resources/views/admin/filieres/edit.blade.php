@extends('layouts.app')
@section('title', 'Modifier la Filière')
@section('header-title', 'Modifier la Filière')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <div class="mb-5 flex items-center gap-2 text-sm text-slate-400">
            <a href="{{ route('admin.filieres.index') }}" class="hover:text-primary-600 transition">Filières</a>
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-600 font-medium">Modifier — {{ $filiere->nom }}</span>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium px-4 py-3 rounded-xl">
                <svg class="h-5 w-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Form Card (2/3) --}}
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
                    {{-- Header --}}
                    <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-violet-50">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                                {{ substr($filiere->code, 0, 2) }}
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-slate-800">{{ $filiere->nom }}</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Modifiez les informations de la filière</p>
                            </div>
                        </div>
                    </div>

                    {{-- Form --}}
                    <form action="{{ route('admin.filieres.update', $filiere->id) }}" method="POST" class="p-6 space-y-5">
                        @csrf @method('PUT')

                        {{-- Nom --}}
                        <div>
                            <label for="nom" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Nom de la filière <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="nom" id="nom"
                                   value="{{ old('nom', $filiere->nom) }}"
                                   class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400
                                          focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition
                                          @error('nom') border-rose-400 bg-rose-50 @enderror">
                            @error('nom')
                                <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Code + Niveau --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="code" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Code <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="code" id="code"
                                       value="{{ old('code', $filiere->code) }}"
                                       maxlength="20"
                                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400
                                              focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition uppercase
                                              @error('code') border-rose-400 bg-rose-50 @enderror">
                                @error('code')
                                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="niveau" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Niveau <span class="text-rose-500">*</span>
                                </label>
                                <select name="niveau" id="niveau"
                                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800
                                               focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition
                                               @error('niveau') border-rose-400 bg-rose-50 @enderror">
                                    @foreach(['L1','L2','L3','M1','M2'] as $niv)
                                        <option value="{{ $niv }}" {{ old('niveau', $filiere->niveau) === $niv ? 'selected' : '' }}>{{ $niv }}</option>
                                    @endforeach
                                </select>
                                @error('niveau')
                                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Département --}}
                        <div>
                            <label for="departement" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Département <span class="text-slate-400 font-normal">(facultatif)</span>
                            </label>
                            <input type="text" name="departement" id="departement"
                                   value="{{ old('departement', $filiere->departement) }}"
                                   class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400
                                          focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition">
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-between gap-3 pt-2">
                            {{-- Delete Button --}}
                            @if($filiere->etudiants_count === 0)
                                <form action="{{ route('admin.filieres.destroy', $filiere->id) }}" method="POST"
                                      onsubmit="return confirm('Supprimer définitivement « {{ $filiere->nom }} » ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-rose-600 bg-rose-50 rounded-xl hover:bg-rose-100 transition">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Supprimer
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-slate-400 italic">
                                    Suppression impossible ({{ $filiere->etudiants_count }} étudiant(s))
                                </span>
                            @endif

                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.filieres.index') }}"
                                   class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">
                                    Annuler
                                </a>
                                <button type="submit"
                                        class="px-6 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow transition">
                                    Enregistrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sidebar Info (1/3) --}}
            <div class="space-y-5">

                {{-- Stats --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 space-y-4">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Statistiques</h3>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Modules</span>
                        <span class="text-sm font-bold text-slate-800">{{ $filiere->modules_count }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Groupes</span>
                        <span class="text-sm font-bold text-slate-800">{{ $filiere->groupes_count }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Étudiants inscrits</span>
                        <span class="text-sm font-bold {{ $filiere->etudiants_count > 0 ? 'text-indigo-700' : 'text-slate-400' }}">
                            {{ $filiere->etudiants_count }}
                        </span>
                    </div>
                </div>

                {{-- Groupes List --}}
                @if($groupes->count() > 0)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Groupes associés</h3>
                    <ul class="space-y-2">
                        @foreach($groupes as $groupe)
                            <li class="flex items-center justify-between text-sm">
                                <span class="text-slate-700 font-medium">{{ $groupe->nom }}</span>
                                <span class="text-xs text-slate-400">{{ $groupe->etudiants_count }} étud.</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Metadata --}}
                <div class="bg-slate-50 rounded-2xl border border-slate-100 p-5 space-y-2">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Informations</h3>
                    <p class="text-xs text-slate-500">
                        Créée le <strong class="text-slate-700">{{ $filiere->created_at->format('d/m/Y') }}</strong>
                    </p>
                    <p class="text-xs text-slate-500">
                        Mise à jour le <strong class="text-slate-700">{{ $filiere->updated_at->format('d/m/Y') }}</strong>
                    </p>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
