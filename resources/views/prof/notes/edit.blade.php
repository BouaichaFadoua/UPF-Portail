@extends('layouts.app')
@section('title', 'Saisie de Notes - ' . $module->nom)
@section('header-title', 'Saisie des Notes · ' . $module->nom)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header details -->
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('prof.notes.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 transition">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour aux modules
            </a>
            
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-900 text-white capitalize">
                    {{ $groupe->nom }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-violet-100 text-violet-800 uppercase">
                    {{ $module->code }}
                </span>
            </div>
        </div>

        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-800">Grille d'Évaluation</h3>
                <p class="text-xs text-slate-500 mt-1">Saisissez les notes sur 20. La note finale est calculée automatiquement selon la formule : (CC moyenne × 40%) + (Examen × 60%).</p>
            </div>

            <form action="{{ route('prof.notes.enregistrer', [$module->id, $groupe->id]) }}" method="POST">
                @csrf

                @if($errors->any())
                    <div class="p-4 mx-6 mt-6 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                        <div class="font-semibold mb-1">Erreurs de validation :</div>
                        <ul class="list-disc pl-5 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Matricule</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Étudiant</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider w-32">CC 1</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider w-32">CC 2</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider w-32">Examen</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider w-32 bg-slate-100/50">Note Finale</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider w-32">Mention</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($etudiants as $index => $etudiant)
                                @php
                                    $note = $etudiant->notes->first();
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-600">
                                        {{ $etudiant->matricule }}
                                        <input type="hidden" name="notes[{{ $index }}][etudiant_id]" value="{{ $etudiant->id }}">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-slate-800">{{ $etudiant->user->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $etudiant->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="number" step="0.25" min="0" max="20" name="notes[{{ $index }}][cc1]" 
                                            value="{{ old('notes.'.$index.'.cc1', $note?->cc1) }}" 
                                            class="w-20 text-center text-sm border border-slate-200 rounded-xl px-2 py-1.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="number" step="0.25" min="0" max="20" name="notes[{{ $index }}][cc2]" 
                                            value="{{ old('notes.'.$index.'.cc2', $note?->cc2) }}" 
                                            class="w-20 text-center text-sm border border-slate-200 rounded-xl px-2 py-1.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="number" step="0.25" min="0" max="20" name="notes[{{ $index }}][examen]" 
                                            value="{{ old('notes.'.$index.'.examen', $note?->examen) }}" 
                                            class="w-20 text-center text-sm border border-slate-200 rounded-xl px-2 py-1.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                                    </td>
                                    <td class="px-6 py-4 text-center bg-slate-50/50">
                                        @if($note && $note->note_finale !== null)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold
                                                {{ $note->note_finale >= 10 ? 'bg-emerald-50 text-emerald-800 border border-emerald-100' : 'bg-rose-50 text-rose-800 border border-rose-100' }}">
                                                {{ number_format($note->note_finale, 2) }} / 20
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400 italic font-medium">Non calculé</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center text-xs font-medium text-slate-600">
                                        @if($note && $note->note_finale !== null)
                                            <span class="capitalize">{{ $note->mention }}</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-400 text-sm">Aucun étudiant dans ce groupe.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Action Bar -->
                @if($etudiants->isNotEmpty())
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <a href="{{ route('prof.notes.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition">Annuler</a>
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow transition flex items-center">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Enregistrer les notes
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection
