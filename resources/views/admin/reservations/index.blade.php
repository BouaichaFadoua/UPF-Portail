@extends('layouts.app')
@section('title', 'Réservations de Salles')
@section('header-title', 'Gestion des Réservations de Salles')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <form action="{{ route('admin.reservations.index') }}" method="GET" class="flex items-center gap-3">
                <select name="statut" class="text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-primary-400">
                    <option value="">Tous les statuts</option>
                    <option value="en_attente" {{ $statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="approuvee" {{ $statut === 'approuvee' ? 'selected' : '' }}>Approuvées</option>
                    <option value="refusee" {{ $statut === 'refusee' ? 'selected' : '' }}>Refusées</option>
                </select>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-slate-700 rounded-xl hover:bg-slate-600 transition">Filtrer</button>
            </form>
            <a href="{{ route('admin.reservations.create') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow transition">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouvelle réservation
            </a>
        </div>

        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Enseignant</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Salle</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Date & Créneau</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Motif</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase">Statut</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($reservations as $res)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-sm font-semibold text-slate-800">{{ $res->professeur->nom_complet }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-slate-800">{{ $res->salle->nom }}</div>
                                    <div class="text-xs text-slate-400 capitalize">{{ $res->salle->type }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-700">{{ $res->date->format('d/m/Y') }}</div>
                                    <div class="text-xs text-slate-400">{{ substr($res->heure_debut, 0, 5) }} – {{ substr($res->heure_fin, 0, 5) }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500 max-w-xs truncate">{{ $res->motif }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        @if($res->statut === 'approuvee') bg-emerald-50 text-emerald-800
                                        @elseif($res->statut === 'en_attente') bg-amber-50 text-amber-800
                                        @elseif($res->statut === 'refusee') bg-rose-50 text-rose-800
                                        @else bg-slate-100 text-slate-600 @endif">
                                        {{ ucfirst($res->statut) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2 flex-wrap">
                                        <a href="{{ route('admin.reservations.edit', $res) }}" class="px-2 py-1 text-xs font-semibold text-sky-600 hover:bg-sky-50 rounded-lg">Modifier</a>
                                        @if($res->statut === 'en_attente')
                                            <form action="{{ route('admin.reservations.approuver', $res) }}" method="POST" class="inline">@csrf
                                                <button type="submit" class="px-2 py-1 text-xs font-semibold text-emerald-600 hover:bg-emerald-50 rounded-lg">Approuver</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.reservations.destroy', $res) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cette réservation ?')">@csrf @method('DELETE')
                                            <button type="submit" class="px-2 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-lg">Supprimer</button>
                                        </form>
                                    </div>
                                    @if($res->statut === 'en_attente')
                                        <div x-data="{ showRefus: false }" class="mt-2">
                                            <button @click="showRefus = !showRefus" class="text-xs text-rose-500 hover:underline">Refuser</button>
                                            <div x-show="showRefus" style="display:none;" class="mt-2">
                                                <form action="{{ route('admin.reservations.refuser', $res) }}" method="POST" class="flex flex-col gap-1">@csrf
                                                    <input type="text" name="motif_refus" required placeholder="Motif du refus…" class="text-xs border rounded-lg px-2 py-1">
                                                    <button type="submit" class="text-xs font-bold text-white bg-rose-600 rounded px-2 py-1">Confirmer</button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-sm">Aucune réservation.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($reservations->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">{{ $reservations->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
