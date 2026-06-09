@extends('layouts.app')
@section('title', 'Détail des Absences')
@section('header-title', 'Absences de ' . $etudiant->user->name)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('admin.absences.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 transition">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour à la liste
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow p-5">
                <p class="text-xs font-bold text-slate-500 uppercase">Total absences</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ $etudiant->total_absences }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-emerald-100 shadow p-5">
                <p class="text-xs font-bold text-emerald-500 uppercase">Justifiées</p>
                <p class="text-2xl font-bold text-emerald-700 mt-1">{{ $etudiant->absences()->where('justifiee', true)->count() }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-rose-100 shadow p-5">
                <p class="text-xs font-bold text-rose-500 uppercase">Non justifiées</p>
                <p class="text-2xl font-bold text-rose-700 mt-1">{{ $etudiant->absences_non_justifiees }}</p>
            </div>
        </div>

        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Module</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Créneau</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($absences as $absence)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-sm font-semibold text-slate-700">{{ $absence->seance->date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-sm text-slate-700">{{ $absence->seance->module->nom }}</td>
                                <td class="px-6 py-4 text-xs text-slate-500">{{ substr($absence->seance->heure_debut, 0, 5) }} - {{ substr($absence->seance->heure_fin, 0, 5) }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($absence->justifiee)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800">Justifiée</span>
                                    @elseif($absence->justificatif)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800">{{ $absence->justificatif->statut === 'en_attente' ? 'En attente' : 'Rejetée' }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-800">Non justifiée</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400 text-sm">Aucune absence enregistrée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($absences->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">{{ $absences->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
