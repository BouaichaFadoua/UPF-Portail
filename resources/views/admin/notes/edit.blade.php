@extends('layouts.app')
@section('title', 'Modifier la Note')
@section('header-title', 'Modifier la Note')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <div class="mb-5 flex items-center gap-2 text-sm text-slate-400">
            <a href="{{ route('admin.notes.index') }}" class="hover:text-primary-600 transition">Notes</a>
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-600 font-medium">Modifier — {{ $note->etudiant->user->name }}</span>
        </div>

        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            {{-- Header --}}
            <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-violet-50">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                        {{ substr($note->etudiant->user->name, 0, 2) }}
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Modifier la note</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Étudiant : {{ $note->etudiant->user->name }} · Module : {{ $note->module->nom }}</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('admin.notes.update', $note->id) }}" method="POST" class="p-6 space-y-5">
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

                {{-- Information Display --}}
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Étudiant</span>
                        <strong class="text-slate-700">{{ $note->etudiant->user->name }} ({{ $note->etudiant->matricule }})</strong>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Module</span>
                        <strong class="text-slate-700">{{ $note->module->nom }} ({{ $note->module->code }})</strong>
                    </div>
                </div>

                {{-- CC1 + CC2 + Examen --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="cc1" class="block text-sm font-semibold text-slate-700 mb-1.5">Note CC1</label>
                        <input type="number" step="0.25" min="0" max="20" name="cc1" id="cc1" value="{{ old('cc1', $note->cc1) }}"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800
                                      focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition">
                    </div>
                    <div>
                        <label for="cc2" class="block text-sm font-semibold text-slate-700 mb-1.5">Note CC2</label>
                        <input type="number" step="0.25" min="0" max="20" name="cc2" id="cc2" value="{{ old('cc2', $note->cc2) }}"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800
                                      focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition">
                    </div>
                    <div>
                        <label for="examen" class="block text-sm font-semibold text-slate-700 mb-1.5">Note Examen</label>
                        <input type="number" step="0.25" min="0" max="20" name="examen" id="examen" value="{{ old('examen', $note->examen) }}"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800
                                      focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition">
                    </div>
                </div>

                {{-- Année universitaire --}}
                <div>
                    <label for="annee_universitaire" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Année Universitaire <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="annee_universitaire" id="annee_universitaire" value="{{ old('annee_universitaire', $note->annee_universitaire) }}" required
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
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
