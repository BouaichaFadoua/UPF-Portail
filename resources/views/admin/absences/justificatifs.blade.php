@extends('layouts.app')
@section('title', 'Justificatifs d\'Absences')
@section('header-title', 'Validation des Justificatifs d\'Absences')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div class="flex gap-2">
                <a href="{{ route('admin.absences.index') }}" class="px-4 py-2 text-sm font-semibold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">Absences & Totaux</a>
                <a href="{{ route('admin.absences.justificatifs') }}" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 rounded-xl">Justificatifs</a>
            </div>
        </div>

        <form action="{{ route('admin.absences.justificatifs') }}" method="GET" class="flex items-center gap-3 mb-6">
            <select name="statut" class="text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-primary-400">
                <option value="">Tous les statuts</option>
                <option value="en_attente" {{ $statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="valide" {{ $statut === 'valide' ? 'selected' : '' }}>Validés</option>
                <option value="rejete" {{ $statut === 'rejete' ? 'selected' : '' }}>Rejetés</option>
            </select>
            <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-slate-700 rounded-xl hover:bg-slate-600 transition">Filtrer</button>
        </form>

        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Étudiant</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Séance absente</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Fichier</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase">Statut</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($justificatifs as $just)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-slate-800">{{ $just->etudiant->nom_complet }}</div>
                                    <div class="text-xs text-slate-400">{{ $just->etudiant->matricule }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-700">{{ $just->absence->seance->date->format('d/m/Y') }}</div>
                                    <div class="text-xs text-slate-400">{{ $just->absence->seance->module->nom }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.absences.justificatifs.telecharger', $just->id) }}" class="inline-flex items-center text-xs font-semibold text-primary-600 hover:text-primary-500">
                                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        {{ $just->fichier_nom }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        @if($just->statut === 'valide') bg-emerald-50 text-emerald-800
                                        @elseif($just->statut === 'en_attente') bg-amber-50 text-amber-800
                                        @else bg-rose-50 text-rose-800 @endif">
                                        {{ $just->statut === 'valide' ? '✓ Validé' : ($just->statut === 'en_attente' ? '⏳ En attente' : '✗ Rejeté') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($just->statut === 'en_attente')
                                        <div class="flex items-center justify-center space-x-2">
                                            <form action="{{ route('admin.absences.justificatifs.valider', $just->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-emerald-600 rounded-lg hover:bg-emerald-500 transition">Valider</button>
                                            </form>
                                            <div x-data="{ open: false }">
                                                <button @click="open = !open" class="px-3 py-1.5 text-xs font-bold text-rose-600 border border-rose-200 rounded-lg hover:bg-rose-50 transition">Rejeter</button>
                                                <div x-show="open" x-cloak class="mt-2 absolute z-10 bg-white shadow-lg rounded-xl border border-slate-200 p-3 w-52">
                                                    <form action="{{ route('admin.absences.justificatifs.refuser', $just->id) }}" method="POST" class="space-y-2">
                                                        @csrf
                                                        <textarea name="motif_rejet" required rows="2" placeholder="Motif du rejet…" class="w-full text-xs border border-slate-200 rounded-lg px-2 py-1 focus:outline-none"></textarea>
                                                        <button type="submit" class="w-full text-xs font-bold text-white bg-rose-600 rounded-lg py-1.5 hover:bg-rose-500">Confirmer</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-slate-300 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-sm">Aucun justificatif à traiter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($justificatifs->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">{{ $justificatifs->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
