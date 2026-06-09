@extends('layouts.app')
@section('title', 'Demandes Administratives')
@section('header-title', 'Gestion des Demandes Administratives')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filters -->
        <form action="{{ route('admin.demandes.index') }}" method="GET" class="flex items-center gap-3 flex-wrap mb-6">
            <select name="statut" class="text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-primary-400">
                <option value="">Tous les statuts</option>
                <option value="en_attente" {{ $statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="validee" {{ $statut === 'validee' ? 'selected' : '' }}>Validées</option>
                <option value="refusee" {{ $statut === 'refusee' ? 'selected' : '' }}>Refusées</option>
            </select>
            <select name="type" class="text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-primary-400">
                <option value="">Tous les types</option>
                @foreach(\App\Models\Demande::$typeLabels as $key => $label)
                    <option value="{{ $key }}" {{ $type === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-slate-700 rounded-xl hover:bg-slate-600 transition">Filtrer</button>
        </form>

        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Demandeur</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($demandes as $demande)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-slate-800">{{ $demande->user->name }}</div>
                                    <div class="text-xs text-slate-400 capitalize">{{ $demande->user->role }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ \App\Models\Demande::$typeLabels[$demande->type] ?? $demande->type }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $demande->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        @if($demande->statut === 'validee') bg-emerald-50 text-emerald-800
                                        @elseif($demande->statut === 'en_attente') bg-amber-50 text-amber-800
                                        @else bg-rose-50 text-rose-800 @endif">
                                        {{ $demande->statut === 'validee' ? 'Validée' : ($demande->statut === 'en_attente' ? 'En attente' : 'Refusée') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.demandes.show', $demande->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-700 border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                                        Voir
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-sm">Aucune demande.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($demandes->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">{{ $demandes->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
