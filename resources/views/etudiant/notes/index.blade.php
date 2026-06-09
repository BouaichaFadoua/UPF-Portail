@extends('layouts.app')
@section('title', 'Mes Notes')
@section('header-title', 'Relevé de Notes')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Banner -->
        <div class="bg-slate-900 rounded-2xl p-6 text-white flex justify-between items-center border border-slate-800 mb-6">
            <div>
                <h2 class="text-xl font-bold">Relevé de notes — {{ auth()->user()->etudiant->annee_universitaire }}</h2>
                <p class="text-slate-400 text-sm mt-1">{{ auth()->user()->etudiant->filiere->nom }} · {{ auth()->user()->etudiant->matricule }}</p>
            </div>
            <div class="text-right">
                <span class="text-slate-400 text-xs uppercase font-semibold tracking-wider">Moyenne générale</span>
                <div class="text-3xl font-black text-primary-400 mt-1">
                    {{ $moyenne !== null ? number_format($moyenne, 2) . ' / 20' : 'N/A' }}
                </div>
                @if($moyenne !== null)
                    <span class="text-sm font-semibold {{ $moyenne >= 10 ? 'text-emerald-400' : 'text-rose-400' }}">
                        {{ $moyenne >= 16 ? 'Très bien' : ($moyenne >= 14 ? 'Bien' : ($moyenne >= 12 ? 'Assez bien' : ($moyenne >= 10 ? 'Passable' : 'Insuffisant'))) }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Notes Table -->
        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Détail par module</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Module</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Coeff.</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">CC1</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">CC2</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Examen</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Note Finale</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Mention</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($notes as $note)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-slate-800">{{ $note->module->nom }}</div>
                                    <div class="text-xs text-slate-400">{{ $note->module->code }}</div>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-slate-600">{{ $note->module->coefficient }}</td>
                                <td class="px-6 py-4 text-center text-sm">
                                    <span class="{{ is_null($note->cc1) ? 'text-slate-300' : ($note->cc1 >= 10 ? 'text-emerald-600' : 'text-rose-500') }} font-semibold">
                                        {{ $note->cc1 !== null ? number_format($note->cc1, 2) : '—' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm">
                                    <span class="{{ is_null($note->cc2) ? 'text-slate-300' : ($note->cc2 >= 10 ? 'text-emerald-600' : 'text-rose-500') }} font-semibold">
                                        {{ $note->cc2 !== null ? number_format($note->cc2, 2) : '—' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm">
                                    <span class="{{ is_null($note->examen) ? 'text-slate-300' : ($note->examen >= 10 ? 'text-emerald-600' : 'text-rose-500') }} font-semibold">
                                        {{ $note->examen !== null ? number_format($note->examen, 2) : '—' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($note->note_finale !== null)
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-bold
                                            {{ $note->note_finale >= 16 ? 'bg-emerald-100 text-emerald-800' :
                                               ($note->note_finale >= 14 ? 'bg-sky-100 text-sky-800' :
                                               ($note->note_finale >= 10 ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800')) }}">
                                            {{ number_format($note->note_finale, 2) }}
                                        </span>
                                    @else
                                        <span class="text-slate-300 text-sm">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-xs font-semibold text-slate-500">
                                    {{ $note->mention ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400 text-sm">
                                    Aucune note disponible pour le moment.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
