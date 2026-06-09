@extends('layouts.app')
@section('title', 'Nouvelle Salle')
@section('header-title', 'Nouvelle Salle')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <div class="mb-5 flex items-center gap-2 text-sm text-slate-400">
            <a href="{{ route('admin.salles.index') }}" class="hover:text-primary-600 transition">Salles</a>
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-600 font-medium">Nouvelle salle</span>
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
                        <h2 class="text-base font-bold text-slate-800">Créer une nouvelle salle</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Ajoutez une salle de cours, de TD ou un amphithéâtre</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('admin.salles.store') }}" method="POST" class="p-6 space-y-5">
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

                {{-- Nom --}}
                <div>
                    <label for="nom" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nom de la salle <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                           placeholder="Ex : Amphi B, Salle 203 (TD)"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition">
                </div>

                {{-- Type + Capacité --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="type" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Type de salle <span class="text-rose-500">*</span>
                        </label>
                        <select name="type" id="type" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 bg-white
                                       focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition">
                            <option value="">-- Choisir un type --</option>
                            <option value="amphitheatre" {{ old('type') === 'amphitheatre' ? 'selected' : '' }}>Amphithéâtre</option>
                            <option value="td" {{ old('type') === 'td' ? 'selected' : '' }}>Salle de TD</option>
                            <option value="tp" {{ old('type') === 'tp' ? 'selected' : '' }}>Salle de TP / Labo</option>
                        </select>
                    </div>
                    <div>
                        <label for="capacite" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Capacité maximale (places assises) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="capacite" id="capacite" value="{{ old('capacite', '40') }}" required
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800
                                      focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition">
                    </div>
                </div>

                {{-- Bâtiment --}}
                <div>
                    <label for="batiment" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Bâtiment / Localisation <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="batiment" id="batiment" value="{{ old('batiment') }}" required
                           placeholder="Ex : Bloc B, 2ème étage"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition">
                </div>

                {{-- Disponible immédiatement --}}
                <div class="flex items-center pt-2">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="disponible" value="1" class="sr-only peer" {{ old('disponible', true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                        <span class="ml-3 text-sm font-semibold text-slate-700">Rendre cette salle disponible immédiatement pour les cours et réservations</span>
                    </label>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('admin.salles.index') }}"
                       class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow transition">
                        Créer la salle
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
