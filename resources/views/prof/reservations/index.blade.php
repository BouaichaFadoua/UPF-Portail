@extends('layouts.app')
@section('title', 'Mes Réservations')
@section('header-title', 'Mes Réservations de Salles')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex justify-end mb-6">
            <a href="{{ route('prof.reservations.create') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow transition">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Réserver une salle
            </a>
        </div>

        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Salle</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date & Horaires</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Motif / Événement</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($reservations as $res)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-8 w-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs">
                                            SA
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-slate-800">{{ $res->salle->nom }}</div>
                                            <div class="text-[10px] text-slate-400">Capacité : {{ $res->salle->capacite }} places</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    <div class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($res->date)->translatedFormat('d M Y') }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ substr($res->heure_debut, 0, 5) }} - {{ substr($res->heure_fin, 0, 5) }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    {{ $res->motif }}
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        @if($res->statut === 'approuvee') bg-emerald-50 text-emerald-800
                                        @elseif($res->statut === 'en_attente') bg-amber-50 text-amber-800
                                        @elseif($res->statut === 'refusee') bg-rose-50 text-rose-800
                                        @else bg-slate-100 text-slate-500 @endif">
                                        @if($res->statut === 'approuvee') ✓ Approuvée
                                        @elseif($res->statut === 'en_attente') ⏳ En attente
                                        @elseif($res->statut === 'refusee') ✗ Refusée
                                        @else 🛇 Annulée @endif
                                    </span>
                                    @if($res->statut === 'refusee' && $res->motif_refus)
                                        <p class="text-[10px] text-rose-500 mt-1 max-w-xs truncate mx-auto" title="{{ $res->motif_refus }}">Motif: {{ $res->motif_refus }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    @if($res->statut === 'en_attente')
                                        <form action="{{ route('prof.reservations.annuler', $res->id) }}" method="POST" onsubmit="return confirm('Retirer cette demande de réservation ?')">
                                            @csrf
                                            <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-800 hover:bg-rose-50 px-3 py-1.5 rounded-lg transition">
                                                Retirer
                                            </button>
                                        </form>
                                    @elseif($res->statut === 'approuvee')
                                        <form action="{{ route('prof.reservations.annuler', $res->id) }}" method="POST" onsubmit="return confirm('Annuler cette réservation approuvée ?')">
                                            @csrf
                                            <button type="submit" class="text-xs font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 px-3 py-1.5 rounded-lg transition">
                                                Annuler
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-slate-300 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-sm">Aucune demande de réservation effectuée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reservations->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $reservations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
