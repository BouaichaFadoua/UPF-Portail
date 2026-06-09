@extends('layouts.app')
@section('title', 'Cahier de Textes')
@section('header-title', 'Mon Cahier de Textes')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Actions & Info -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div class="bg-white shadow rounded-2xl border border-slate-100 px-6 py-4 flex-1">
                <h3 class="text-base font-bold text-slate-800">Suivi du Cahier de Textes</h3>
                <p class="text-xs text-slate-500 mt-1">Consignez l'avancement de vos programmes académiques en y ajoutant les objectifs et contenus enseignés.</p>
            </div>
            
            <a href="{{ route('prof.cahier-textes.create') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow transition flex-shrink-0">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouvelle entrée
            </a>
        </div>

        <!-- Cahier text list -->
        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date & Horaires</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Module</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Nature</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Objectif de la séance</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Contenu / Avancement</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($cahiers as $cahier)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 text-sm font-semibold text-slate-600 whitespace-nowrap">
                                    <div class="text-slate-800 font-bold">{{ \Carbon\Carbon::parse($cahier->date)->translatedFormat('d M Y') }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ substr($cahier->heure_debut, 0, 5) }} - {{ substr($cahier->heure_fin, 0, 5) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-slate-800">{{ $cahier->module->nom }}</div>
                                    <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold">{{ $cahier->module->code }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                        {{ $cahier->nature === 'Cours' ? 'bg-sky-50 text-sky-800' :
                                           ($cahier->nature === 'TD' ? 'bg-violet-50 text-violet-800' : 'bg-emerald-50 text-emerald-800') }}">
                                        {{ $cahier->nature }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700 max-w-xs">
                                    <div class="line-clamp-2" title="{{ $cahier->objectif }}">{{ $cahier->objectif }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500 max-w-sm">
                                    <div class="line-clamp-2" title="{{ $cahier->contenu ?? 'Aucun détail' }}">{{ $cahier->contenu ?? '—' }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-sm">Aucun compte rendu rédigé pour le moment.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($cahiers->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $cahiers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
