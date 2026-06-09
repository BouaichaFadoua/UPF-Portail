@extends('layouts.app')
@section('title', 'Nouvelle Filière')
@section('header-title', 'Nouvelle Filière')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <div class="mb-5 flex items-center gap-2 text-sm text-slate-400">
            <a href="{{ route('admin.filieres.index') }}" class="hover:text-primary-600 transition">Filières</a>
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-600 font-medium">Nouvelle filière</span>
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
                        <h2 class="text-base font-bold text-slate-800">Créer une nouvelle filière</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Remplissez les informations ci-dessous</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('admin.filieres.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

                {{-- Nom --}}
                <div>
                    <label for="nom" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nom de la filière <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nom" id="nom" value="{{ old('nom') }}"
                           placeholder="Ex : Génie Informatique"
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
                        <input type="text" name="code" id="code" value="{{ old('code') }}"
                               placeholder="Ex : GI"
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
                            <option value="">Choisir…</option>
                            @foreach(['L1','L2','L3','M1','M2'] as $niv)
                                <option value="{{ $niv }}" {{ old('niveau') === $niv ? 'selected' : '' }}>{{ $niv }}</option>
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
                    <input type="text" name="departement" id="departement" value="{{ old('departement') }}"
                           placeholder="Ex : Département Informatique et Technologies"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition">
                </div>

                {{-- Info box --}}
                <div class="flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-xl p-4 text-xs text-blue-700">
                    <svg class="h-4 w-4 mt-0.5 flex-shrink-0 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p>Après la création, vous pourrez ajouter des <strong>groupes</strong> et des <strong>modules</strong> à cette filière depuis les modules correspondants.</p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('admin.filieres.index') }}"
                       class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow transition">
                        Créer la filière
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
