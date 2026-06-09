@extends('layouts.app')
@section('title', 'Gestion des Absences')
@section('header-title', 'Appel & Présences')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white shadow rounded-2xl border border-slate-100 p-6 mb-6">
            <h3 class="text-base font-bold text-slate-800">Séances de Cours</h3>
            <p class="text-xs text-slate-500 mt-1">Sélectionnez une séance ci-dessous pour faire l'appel ou modifier les présences des étudiants.</p>
        </div>

        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date & Heure</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Module</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Groupe</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Salle</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Statut Appel</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($seances as $seance)
                            @php
                                $absencesCount = $seance->absences()->count();
                            @endphp
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-sm font-semibold text-slate-600">
                                    <div class="text-slate-800 font-bold">{{ \Carbon\Carbon::parse($seance->date)->translatedFormat('l d F Y') }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ substr($seance->heure_debut, 0, 5) }} - {{ substr($seance->heure_fin, 0, 5) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-slate-800">{{ $seance->module->nom }}</div>
                                    <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold">{{ $seance->type }} · {{ $seance->module->code }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary-50 text-primary-800">
                                        {{ $seance->groupe->nom }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-medium text-slate-600">
                                    {{ $seance->salle->nom }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($absencesCount > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-100">
                                            {{ $absencesCount }} absent(s)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-100">
                                            Présence complète
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('prof.absences.appel', $seance->id) }}" class="inline-flex items-center px-3.5 py-1.5 text-xs font-bold text-white bg-primary-600 rounded-lg hover:bg-primary-500 shadow-sm transition">
                                        Faire l'appel
                                        <svg class="ml-1 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-sm">Aucune séance programmée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($seances->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $seances->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
