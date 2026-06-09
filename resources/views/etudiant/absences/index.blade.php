@extends('layouts.app')
@section('title', 'Mes Absences')
@section('header-title', 'Suivi des Absences')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Résumé des absences par module -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Cumul des absences par module</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($absencesParModule as $item)
                    <div class="bg-white dark:bg-slate-850 p-5 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ $item->module_code }}</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $item->total > 3 ? 'bg-rose-50 text-rose-800 dark:bg-rose-950/30 dark:text-rose-400' : 'bg-amber-50 text-amber-800 dark:bg-amber-950/30 dark:text-amber-400' }}">
                                    {{ $item->total }} {{ Str::plural('absence', $item->total) }}
                                </span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $item->module_nom }}</h4>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex justify-between text-xs text-slate-500 dark:text-slate-400">
                            <span>Justifiées : <strong>{{ (int)$item->justifiees }}</strong></span>
                            <span>Non justifiées : <strong class="text-rose-600 dark:text-rose-400">{{ (int)$item->non_justifiees }}</strong></span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-slate-50 dark:bg-slate-900/50 p-6 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 text-center text-slate-400 text-sm">
                        Aucune absence à signaler.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Historique des absences</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Module</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Créneau</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($absences as $absence)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-sm text-slate-700 font-semibold">
                                    {{ $absence->seance->date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-slate-800">{{ $absence->seance->module->nom }}</div>
                                    <div class="text-xs text-slate-400">{{ $absence->seance->type }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500">
                                    {{ substr($absence->seance->heure_debut, 0, 5) }} - {{ substr($absence->seance->heure_fin, 0, 5) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($absence->justifiee)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800">
                                            ✓ Justifiée
                                        </span>
                                    @elseif($absence->justificatif)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            {{ $absence->justificatif->statut === 'en_attente' ? 'bg-amber-50 text-amber-800' : 'bg-rose-50 text-rose-800' }}">
                                            {{ $absence->justificatif->statut === 'en_attente' ? '⏳ En attente' : '✗ Rejeté' }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-800">
                                            Non justifiée
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if(!$absence->justifiee && !$absence->justificatif)
                                        <a href="{{ route('etudiant.absences.justifier.form', $absence->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 border border-primary-200 text-xs font-semibold rounded-lg text-primary-700 bg-primary-50 hover:bg-primary-100 transition">
                                            Déposer un justificatif
                                        </a>
                                    @elseif($absence->justificatif && $absence->justificatif->statut === 'rejete')
                                        <span class="text-xs text-slate-400 italic">Motif : {{ $absence->justificatif->motif_rejet }}</span>
                                    @else
                                        <span class="text-slate-300 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-sm">
                                    <svg class="h-12 w-12 text-slate-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Aucune absence enregistrée. Félicitations !
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($absences->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $absences->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
