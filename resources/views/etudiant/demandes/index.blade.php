@extends('layouts.app')
@section('title', 'Mes Demandes')
@section('header-title', 'Demandes de Documents Administratifs')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-end mb-6">
            <a href="{{ route('etudiant.demandes.create') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow transition">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouvelle demande
            </a>
        </div>
        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Type de document</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date demande</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Document PDF</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($demandes as $demande)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-slate-800">{{ \App\Models\Demande::$typeLabels[$demande->type] ?? $demande->type }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $demande->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        @if($demande->statut === 'validee') bg-emerald-50 text-emerald-800
                                        @elseif($demande->statut === 'en_attente') bg-amber-50 text-amber-800
                                        @else bg-rose-50 text-rose-800 @endif">
                                        @if($demande->statut === 'validee') ✓ Validée
                                        @elseif($demande->statut === 'en_attente') ⏳ En attente
                                        @else ✗ Refusée @endif
                                    </span>
                                    @if($demande->statut === 'refusee' && $demande->motif_refus)
                                        <p class="text-xs text-rose-400 mt-1">{{ $demande->motif_refus }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($demande->statut === 'validee' && $demande->document)
                                        <a href="{{ route('admin.demandes.telecharger', $demande->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-500 transition">
                                            <svg class="h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            Télécharger
                                        </a>
                                    @else
                                        <span class="text-slate-300 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400 text-sm">Aucune demande de document. Cliquez sur "Nouvelle demande" pour commencer.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
